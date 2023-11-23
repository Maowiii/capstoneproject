<?php
namespace App\Imports;

use App\Models\Employees;
use App\Models\Accounts;
use App\Models\EvalYear;
use App\Models\Appraisals;
use App\Models\Departments;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;


use Throwable;

//use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ImportEmployeeSample implements ToModel, WithHeadingRow, SkipsOnFailure, SkipsEmptyRows, WithValidation
{
    use Importable, SkipsFailures;

    private $requiredColumns = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'type',
        'department',
    ];

    public function model(array $row)
    {
        // $missingColumns = $this->getMissingColumns($row);

        // if (!empty($missingColumns)) {
        //     throw new \Exception('Uploaded Excel is missing the following required columns: ' . implode(', ', $missingColumns));
        // }

        if (
            empty($row['employee_number']) &&
            empty($row['first_name']) &&
            empty($row['last_name']) &&
            empty($row['email']) &&
            empty($row['type']) &&
            empty($row['department'])
        ) {
            return null;
        }

        // Generate a random password
        $randomPassword = Str::random(8);

        // Create an Accounts instance
        $account = Accounts::updateOrCreate(
            ['email' => $row['Email'] ?? $row['email']],
            [
                'default_password' => $randomPassword,
                'type' => $row['type'] ?? $row['type'],
                'first_login' => 'true',
            ]
        );

        $account_id = $account->account_id;

        $departmentID = Departments::where('department_name', $row['Department'] ?? $row['department'] ?? $row[5])->pluck('department_id')->first();

        // Create an Employees instance
        $employee = Employees::updateOrCreate(
            ['employee_number' => $row['Employee Number'] ?? $row['employee_number'] ?? $row['employee_number'] ?? $row[0]],
            [
                'account_id' => $account_id,
                'first_name' => $row['First Name'] ?? $row['first Name'] ?? $row['first_name'] ?? $row[1],
                'last_name' => $row['Last Name'] ?? $row['last Name'] ?? $row['last_name'] ?? $row[2],
                'email' => $row['Email'] ?? $row['email'],
                'type' => $row['type'] ?? $row['type'],
                'department_id' => $departmentID,
            ]
        );

        // Save the Employees instance to the database
        $employee->save();

        // Log info message
        Log::info('Employee imported successfully: ' . $employee->employee_id);

        // Handle the rest of your logic here, using $employee
        $activeYear = EvalYear::where('status', 'active')->first();

        if ($activeYear && !in_array($account->type, ['AD', 'IS', 'CE'])) {
            $evaluationTypes = ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'];

            // Check if the employee has existing appraisal records
            $existingAppraisals = Appraisals::where('employee_id', $employee->employee_id)->count();

            // If no existing appraisal records, create new ones
            if ($existingAppraisals == 0) {
                foreach ($evaluationTypes as $evaluationType) {
                    $evaluatorId = null;

                    if ($evaluationType === 'self evaluation') {
                        $evaluatorId = $employee->employee_id;
                    } elseif ($evaluationType === 'is evaluation') {
                        $departmentId = $employee->department_id;
                        $isAccount = Accounts::where('type', 'IS')
                            ->whereHas('employee', function ($query) use ($departmentId) {
                                $query->where('department_id', $departmentId);
                            })->first();

                        if ($isAccount) {
                            $evaluatorId = $isAccount->employee->employee_id;
                        }
                    }

                    Appraisals::create([
                        'evaluation_type' => $evaluationType,
                        'employee_id' => $employee->employee_id,
                        'evaluator_id' => $evaluatorId,
                        'department_id' => $departmentID,
                    ]);
                }
            }
        }

        // Log info message
        Log::info('Employee appraisals created: ' . $employee->employee_id);
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|ends_with:adamson.edu.ph|unique:accounts,email|distinct',
            'employee_number' => 'required|max:11|unique:employees,employee_number',
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => 'required',
            'department' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter an Adamson email address.',
            'email.ends_with' => 'Please enter an Adamson email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email is already in use',
            'employee_number.required' => 'Please enter an employee number.',
            'employee_number.unique' => 'The employee number is already in use.',
            'employee_number.max' => 'Please enter a valid employee number.',
            'first_name.required' => 'Please enter the employee\'s first name.',
            'last_name.required' => 'Please enter the employee\'s last name.',
            'type.required' => 'Please choose a user level.',
            'department.required' => 'Please choose a department.'
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function uniqueBy()
    {
        return 'employee_number';
    }

    public function onError(Throwable $error)
    {
        return messages();
    }

    // private function getMissingColumns(array $row)
    // {
    //     // Find missing required columns in the $row array
    //     $missingColumns = [];

    //     foreach ($this->requiredColumns as $column) {
    //         if (!array_key_exists($column, $row)) {
    //             $missingColumns[] = $column;
    //         }
    //     }

    //     return $missingColumns;
    // }
}
