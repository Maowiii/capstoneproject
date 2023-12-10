<?php

namespace App\Imports;

use App\Models\Employees;
use App\Models\Accounts;
use App\Models\EvalYear;
use App\Models\Appraisals;
use App\Models\Departments;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ImportEmployee implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;
    private $successCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {        
        if (!array_filter($row)) {
            return;
        }

        if (
            !empty($row['employee_number']) &&
            !empty($row['first_name']) &&
            !empty($row['last_name']) &&
            !empty($row['email']) &&
            !empty($row['type']) &&
            !empty($row['department'])
        ) {
            // Generate a random password
            $randomPassword = Str::random(8);

            $email = trim($row['Email'] ?? $row['email']);
            $accType = trim($row['Type'] ?? $row['type']);
            $deptName = trim($row['Department'] ?? $row['department']);
            $firstName = trim($row['First Name'] ?? $row['first Name'] ?? $row['first_name']);
            $lastName = trim($row['Last Name'] ?? $row['last Name'] ?? $row['last_name']);
            $empNum = trim($row['Employee Number'] ?? $row['employee_number'] ?? $row['employee_number']);

            // Create an Accounts instance
            $account = Accounts::updateOrCreate(
                ['email' => $email],
                [
                    'email' => $email,
                    'default_password' => $randomPassword,
                    'type' => $accType,
                    'first_login' => 'true',
                ]
            );

            $account_id = $account->account_id;

            $departmentID = Departments::where('department_name', $deptName)->pluck('department_id')->first();

            // Create an Employees instance
            $employee = Employees::updateOrCreate(
                ['employee_number' =>  $empNum ],
                [
                    'account_id' => $account_id,
                    'first_name' => $firstName, 
                    'last_name' => $lastName,
                    'department_id' => $departmentID,
                ]
            );

            if(!in_array($account->type, ['AD', 'IS', 'CE'])){
                $isAccount = Accounts::where('type', 'IS')
                ->whereHas('employee', function ($query) use ($departmentID) {
                    $query->where('department_id', $departmentID);
                })->first();

                $immediateSuperior = $isAccount->employee->employee_id;

                Employees::where('account_id', $employee->account_id)
                    ->update([
                        'immediate_superior_id' => $immediateSuperior,
                    ]);
            }

            // Handle the rest of your logic here, using $employee
            // $activeYear = EvalYear::where('status', 'active')->first();

            // Log::info($activeYear);

            // if ($activeYear && !in_array($account->type, ['AD', 'IS', 'CE'])) {
            //     $evaluationTypes = ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'];

            //     // Check if the employee has existing appraisal records
            //     $existingAppraisals = Appraisals::where('employee_id', $employee->employee_id)->count();

            //     // If no existing appraisal records, create new ones
            //     if ($existingAppraisals == 0) {
            //         foreach ($evaluationTypes as $evaluationType) {
            //             $evaluatorId = null;

            //             if ($evaluationType === 'self evaluation') {
            //                 $evaluatorId = $employee->employee_id;
            //             } elseif ($evaluationType === 'is evaluation') {
            //                 $departmentId = $employee->department_id;
            //                 $isAccount = Accounts::where('type', 'IS')
            //                     ->whereHas('employee', function ($query) use ($departmentId) {
            //                         $query->where('department_id', $departmentId);
            //                     })->first();

            //                 if ($isAccount) {
            //                     $evaluatorId = $isAccount->employee->employee_id;
            //                 }
            //             }

            //             Appraisals::create([
            //                 'evaluation_type' => $evaluationType,
            //                 'employee_id' => $employee->employee_id,
            //                 'evaluator_id' => $evaluatorId,
            //                 'department_id' => $departmentID,
            //             ]);
            //         }
            //     }
            //     $this->successCount++;
            //     Log::info('Employee Import was Success!');
            //     return;
            // } 
        } else {
            return;
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function rules(): array
    {
        return [
            '*.email' => ['required', 'email', 'ends_with:adamson.edu.ph', 'unique:accounts,email', 'distinct'],
            '*.employee_number' => ['required', 'numeric', 'digits_between:11,11', 'unique:employees,employee_number', 'distinct'],
            '*.first_name' => 'required|string',
            '*.last_name' => 'required|string',
            '*.type' => 'required|string',
            '*.department' => 'required|string'
        ];
    }
}
