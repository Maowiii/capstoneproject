@extends('layout.master')

@section('title')
<h1>Internal Customer Evaluation Form</h1>

@endsection

@section('content')
<div class="content-container">

    <!-- <div id="progressBarHandler" class="progress" style="height: 10px;">
        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
    </div> -->

    <h3>Dear Adamson Employee,</h3>
    <p>Each department (co-academic offices) wants to know how well we are serving our employees/internal customers. We
        appreciate if you would take the time to complete this evaluation. This is a feedback mechanism to improve our
        services. Your answers will be treated with utmost confidentiality.</p>
    <p>Your opinion matters. We welcome your comments and suggestions. Please answer the questions by selecting one
        option from the provided choices using the radio buttons. This will make us aware of a problem, complaints, or
        an opportunity to make a suggestion, and to recognize or commend for a job well done.</p>

    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <h5>Name of the staff to be evaluated:</h5>
        </div>
        <div class="col-auto">
            <input class="form-control" type="text" id="appraiseeName" disabled>
        </div>

        <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
            Choose each number which corresponds to your answer for each item. Please answer each item truthfully.<br>
            5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever
        </p>

        <div class="d-grid gap-3">
            <button type="button" class="btn btn-primary col-3" id="sendrequest"> <i class="bi bi-envelope-paper"></i>
                Send Request</button>
            <div id="feedback-container" class="alert alert-info d-none" role="alert">
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Feedback:</strong> <span id="feedback-text"></span>
                <hr>
                <div id="additional-info" class="font-italic"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="IC_table">
                <thead>
                    <tr>
                        <th class="xxs-column">#</th>
                        <th>Question</th>
                        <th class="large-column">Score</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class='text-right'>Total Weighted Score:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input id="total-weighted-score"
                                    class="small-column form-control total-weighted text-center" type="text" readonly>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p>What did you like best about his/her customer service?</p>
        <textarea class="form-control" id="service_area"></textarea>

        <p class="mt-3">Other comments and suggestions:</p>
        <textarea class="form-control" id="comments_area"></textarea>
    </div>
    <div class="d-flex justify-content-center gap-3  p-3">
        <button type="submit" class="btn btn-primary medium-column" id="submit-btn">Submit</button>
    </div>

    <div class="modal fade" id="signatory_modal" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="signatory">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Signatories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive" id="signtable">
                        <thead>
                            <tr>
                                <th scope="col" style="width:20%" id="partieshead">PARTIES</th>
                                <th scope="col" style="width:20%" id="fullnamehead">FULL NAME</th>
                                <th scope="col" style="width:25%" id="signhead">SIGNATURE</th>
                                <th scope="col" style="width:15%" id="datehead">DATE</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="alert alert-danger d-none" role="alert" id="error-alert">
                    </div>
                    <div class="alert alert-warning" role="alert" id="confirmation-alert">
                        Once you have submitted the form, you cannot alter any values.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancel-btn" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="esig-submit-btn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="imageModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Signature Preview</h5>
                    <button type="button" class="btn-close" id="esig-close-btn"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Signature" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-lg" id="request-popup-modal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5">REQUEST FORM</h5>
                <button type="button" class="btn-close common-close-button" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('submitRequest') }}" method="POST" id="sendReqForm">
                @csrf
                <div class="modal-body">
                    <div id="validation-results" class="alert alert-danger" style="display: none;">
                        <ul id="validation-list"></ul>
                    </div>
                    <h5>Instructions:</h5>
                    <p class='text-justify'>This request form is designed to allow send request to have another attempt
                        in accomplishing the appraisal form.
                        Kindly provide the details of your request and any additional notes in the field provided.Thank
                        you</p>
                    <label for="requestText">
                        <h5>Request:</h5>
                    </label>
                    <textarea name="request" id="requestText" class="form-control"
                        placeholder="Enter your request here..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(  documen t ).read y( funct io
     ()
    {
         $( '#sen drequest' ).hide();

         $( '#se rvice _area' ).on( 'bl ur
        , function ()
        {
             v ar newService = $( this ).val();
              updateServic e( newService  );
        } );
 
         $( '#comment s_
        rea' ).on( 'blur', function ()
         { 
            var newSuggestion = $( th is ).val();
             u pdateSuggestion( newSuggestion );
        } );

        // Function to retrieve URL paramet e rs b y
        name
        function getUrlParame ter ( name )
         {
             n ame = name.replace( /[\[]/, '\\[' ).re place( /[\]]/, '\\]' );
             var regex = new RegExp( '[\\?&]'  + name + '=([^& #]*)' );
            var results = regex.exec( location.search ); 
            return  results = = = null ? '' : decodeURIComponent( results[1].replace( /\+/g, ' ' ) );
        }

        // Get the employ ee's name from t he URL
        var appraiseeName = getUrlParameter( 'appraisee_name' ) ;

        //  
        et the employee' s name in the in put fi eld
        i f ( appraiseeName
        )
        {
              $( '#appraiseeName' ).va l( appraiseeNam e );
        } else
         {
            $( ' .modal' ).hide();
            $( '.content-body' ).remove();
            $( '.cont ent-container ' ).remove();

            // Create a new div element
            var errorDiv = $( '<div></d iv>' );

             // Set the attributes and content  for the error div
            errorDi v.attr( 'id', 'error-message'  );
            errorDiv.addClass( 'alert alert-danger content-con tainer' );
            errorDiv.text( 'An error occurred: You do not have permission to view this form.' );

             // App end the error div to a s pecific element (e .g.,  the body)
          
          errorDiv.appendTo( '.content-s ection'  );
        }

        $( '#esig-submit-btn' ).on( 'cl ick', function ()
         {
            var fileInput = $( '#esig' )[ 0];
             var urlParams = new URLSearchParams( win dow.location.search );
             var appraisalId = urlParams.get( 'appraisal_id' );
            var totalWeightedScore = $ ( '#total-weighted-score' ). v
            l();
            //  console .log('Total  Weighted Sc ore: ' + totalWeightedScore);

            if
            ( fileInput.files.length === 0 )
            {
                $( '#esig' ).ad dClass( 'is-i nvalid' );
     
                            return;
              } else
             {
                 var selecte dFile = fileIn put.files[0];
 
                 if ( !isImageFile( selectedFile ) )
                {
                    $( '#esig' ).addCla ss( 'is-invalid' );

                     $(  '
                    error-alert' ).removeClass(  'd-none' ).tex t(
                          'Please select a valid  image file. Supported formats: JPEG, PNG, GIF.' );

                    setTimeout( function ()
                    {
                          $ (
                '#error-alert' ).addClass( 'd-none' );
                    }, 5000 );

                     return;
                }

                var reader = new FileReader();
                 reader.onloa d = fun ction ( e vent )
                {
                    var fileData = event.target.result;
                    $.ajax( {
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        url: '{{ route('pe.submitICSignature') }}',
                        type: 'POST',
                        data: {
               
                                       appraisalId: apprai salId,
           
                                             esignature: fileData,
                            totalWeightedScore: totalWeightedScore
                        },
                        success: function ( response )
               
                                    {
                            if ( response.success )
                            {
                                  loadSignat ure();
                                 // console.log('Esignatur e Updated.');
                                f ormCheck er
                                );
                            } else
                               {
                                 var errorMessag e = response.message;

                                $( '#error-alert' ).removeClass( 'd-none' ). t ext(
                                      err orMessage );

                                setTimeout( fu nction ()
                                  {
                                   
           $( '#error-alert' ).addClass( 'd-none'  );
                                 }, 5000 );
                   
                   }
                        },
                         error: f unction ( xhr, status, error ) { }
                     } );
                 };

                 reader.readAsDataURL( selectedFile );
            }
         } );

        functi on isIm ageFile (  file )
        {
            return file.type.startsWith( 'image/' );
        }

        function updateSuggestion ( value )
        {
            var urlParams = new URLSearchParams( window.location.search );
            var appraisalId = urlParams.get( 'appraisal_id'   );

      
                      $.ajax( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name= "csrf-token"]' ) .attr( 'conten t' )
                 },
                url: '{{ route('updateSug g est i
                n') }}',
                t ype: 'POST',
     
                               data: {
                    newSuggestion: value,
                    appraisalId: appr
                    isalId
                },
                success: function ( response )
                {
                    // con sole.log('Backend updated successfully.');
           
                   $( '#comments_area' ).removeClass( 'is-invalid' );
                },
                 error: fun ction ( xhr )
                {
                     if ( xhr. responseText )
                     {
                        // console.log('Error: ' + xh r.responseText);
                      } el se
                    {
                        // console.log('An error occurred.');
                    }
                }
            } );
        }

        function updateService ( value )
        {
            // console.log(value);
            var urlPara m s = new  U
                LSearchParams( window.location.search );
            var appraisalId = urlParams.get( 'apprai sal_id' );

             $.aja x( {
                 headers: {
                    'X-CSRF-TOKEN ' : $ (
                'meta[name="csrf-token"]'  ).attr( 'content '
                    )
                },
                url: '{{ route('updateService') }}',
                type: 'P
                    ST',
                data: {
                    newService: value,
                    appraisalId: appraisalId
                 },
                success: f un
        tion ( response )
                {
                     // console.log('Backend updated success fully.' );
        
                        $( '#service_area' ).remove Cl ass(  'is-in valid' );
                },
                err or: function ( xhr )
                 {
                     if ( xhr.responseText )
                    {
                        / / console.log('Error: '  + xhr .responseText);
                       } else
                    {
     
                           // console.log('An error occurr ed.');
                     }
                }
            } );
         }

         function totalScore () 
        {
            var total = 0;

            $( '#IC_table .f orm-check-input[type="rad io"]:ch ecked' ). each( function ()
            {
                var score = parseInt( $( this ).val() );
                total += score;
            } );

            numQuestions = $( '#IC_table tbody tr' ).length;
            var averageScore = total  /  numQues t
                ons;
            $( '#tota l-weighted-score '
                    ).val( averageScore.toFixed(  2 ) );
         }

         function loadTextA reas ()
        {
             var urlParams =  new U RLSearchParams( win dow.location.search );
      
                         var appraisalId = urlParams.get( 'appraisal_id' );
            $.ajax( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="cs r f-t o
                en"]' ).attr( 'content' )
                  }
                    
                url: '{{ route('getCommentsAndSuggestions') }}',
                type: 'POST',
  
                                 data: {
                    appraisalId: appraisalId
                },
                success: functio n ( response )
                 {
                      if ( response.success )
                  
          {
                        $( '#se rvic e_area' ).val( response.customerService );
                         $( '#co mments_area' ).val( response.suggestion );
                      } else
                    {
                         //  console.log('Comments not found or an error occurred .') ;
                    }
                },
                error: function ( xhr )
                {
                    if ( x hr.responseText )
                    {
                        //  console.log('Error: ' + x hr.resp onseText) ;
                    } else
                    {
                        // console.log('An error occurred.');
                    }
                }
            } );
        }

        $( '#IC_table' ).on( 'click', '.form-check-input[type="radio"]', function ()
        {
            var clickedRad i o = $( t h
                s );

            var urlParams = new URLSearchParams( window.location.search );
            var appraisalId = urlParams.ge t( ' appraisal_id' );

            var radioButtonId = cli ckedRadio.attr( 'id' );
            var questionId = radioButtonId.split( '_' )[1];
           
                   var score = clickedRadi o.val();
         
                       // console.log('Question ID: ', questionId);

            $.ajax( {
                headers: {

                                       'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                 url: '{{ r oute('saveICScores') }}',
         
               type: 'POST',
                 data: {
                    questionId: questionId, 
                    scor e: scor e,
                     appraisalId: appraisalId
                },
                success: function ( response )
                {
                    // console.l o g('Score  
                aved for question ID:', qu estionId);
       
                                 clickedRadio.closest( 'tr'  ).removeClass(
                         'table-danger' );
                    totalScore();
                },
                error: function  ( xhr )
                 { 
                     
                        f ( xhr.responseText )
                    {
                        // console.log('Error: ' + xhr.responseText);
                    } else
                    {
                        //  console.log('A n error occurred.');
                    }
                }
            } );
        } );

        function loadICTable ()
        {
             $.ajax( { 
                headers: {
                    'X-CSR F-TOKEN': $( 'meta[nam e="csrf-token"]' ).attr( 'content' )
                },
                url: '/editable-internal-customer-form/getICQuestions',
                type: 'GET',
                success: function ( response )
                {
                    if ( response.success )
                    {
                        var tbody = $( '#IC_table tbody' );
                        tbody.empty();

                        var quest ionCounter  = 1;

                          $.each( response.ICques, function ( index, formquestions )
                        {
                            va r question Id = formquestions.question_id;

                            var row = `<tr>
                                            <td class="align-middle">${ questionCounter }</td> <!-- Display the counter -->
                                            <td class="align-baseline text -st art editable" data-questionid="${ questionId } ">
                                                 ${ formquestions.question }
                                     
                           </td>
                                            <td class="align-middle likert-column">
                                                  @for ($i = 5; $ i
                >= 1; $i--)
                                                    <div class=" form-check form-check-inline">
                             
                                     <input class="form-ch eck-input" type="radio " id="score_${ questionId }" name="ic_${ questi onId }" value= "{{ $i }}">
                                                         <label class="form-check-label" for="score_${ questionId }">{{  $i }}</label>
                                                       </div>
                                                @endfor
                                            </td>
                                        </tr>`;
                            tbody.append( row );
                            loadSave d Score( questionId  )
                
                             questionCounter++;
      
                                      } );

                    } else
                    {
                         // console.log('Err o
                        :' + response.error);
                     }
                  },
                 erro r: f unction ( xhr, status, error )
                 {
                     // console.log(error);
                }
            } );
        }

        function loadSavedScore ( questionId )
          {
            va r
                urlParams = new URLSearchParams( window.location.search );
            var a ppraisalId = urlParams.get( 'appraisal_id' ) ;

                    // console.log(appraisalId);
             $.ajax( {
                 headers: {
                    'X-CSRF-T OKEN': $( 'met a[name="csrf-token"]' ) .attr( 'content' )
                },
                url: '{{ rout e('getSavedICScores') }}' ,
                  type: 'GET',
                data: {
                    appraisalId: appraisalId,
                    questionId: questionId
                },
                success: function ( savedScoreResponse )
                {
                    
                  if ( savedScoreResponse. success )
        
                                {
                         var save dScore = savedScoreResponse.score;
                          if ( sav edScore !== nu ll )
                        {
                                $( `input[name=" i c_${ questionId }"][value="${ savedScore  }" ]` )
                                    .prop( 'checked', true ); 
                          }
                     }
                    totalSco re();
              
                          },
                error: function ( xhr, status, error )
                {
                    // c onsole.log(er ror);
                }
            } ); 
        }

         function loadSignature ()
        {
            va r  urlPar ams = new U RLSearchParams ( window.location.search );
            var appraisalId = urlParams.get( 'appraisal_id' );

            $.ajax( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                 },
               
                        url: '{{ route('pe.loadSignatures') }}',
                type: 'GET',
                data: {
                    appr ai salId:  appraisalI d,
                 },
                success: function ( response )
                {
                    if ( response.success )
                    {
                        $( '#signtable tbody' ).empty();
                        var newRow = $( '<tr>' ).addClass( 'align-middle' );
                        newRow.append( $( '<td>' ).text(  'Internal Customer' ) );
                        newRow.ap pend( $( '<td>' ).text(  
                        esponse.full_name ) );

                          $( ' #modalI mage' ).attr( 'src', re s ponse.sign_data );

             
                                  if ( response.sign_data )
                            {
                              // console.log('Response Data  Received');
                               $( '#cancel-btn' ).hid
                    ();
                            $( '#esig-submit-btn' ).hide();
                            newRow.append( $( '<td>' ).add C lass( 'align-middl e
                 ).html(
                                '<button class="btn btn-outline-pri mary" id="view-sig-btn">' +
                 
                       'View Signature' +
                                 '</but ton>'
                            ) );
                          } else
                        {
                             // con sole.log('Response data not received');
                            newRow.append(  $( '<td>' ).addClass( 'align-middle' ).html(
                                 '<div>' +
                                   '<input type="file" id="esig" class="form-control" accept="image/jpeg, image/png, image/jpg">' +
                                '<img src="" width="100" id="signatureImage">' +
                                '</div>'
                            ) );
                         
                 }

                        if ( r esponse. date_submitted )
                         {
         
                                       newRow.ap pend( $( '<td>' ).tex t( resp onse.date_submit ted ) );
                         } else 
                          {
                             newRow.append( $(  '<td>' ).te xt( '-'  ) );
                         }

                           $( '#signtable tbody' ).append(  newRow );

            
                                } else
                     {
                         // console.log('fail');
                    }
                 },
       
                                 error: function ( xhr, st atus, error )
                 
                            
                    // console.log( error);
                 }
             } ) ;
          }

        function formChecker ()
         {
             var  urlParams = new URLSe archPar ams( window.lo cation. search );
             var  appr aisalId = u rlParams.get( 'apprais al_id' );
            var appraiseeId = ur lParams.get( 'appraisee_account_id' );

            // console.log( '
                            ppraisal ID: ' + appraisalId)

            $.ajax( {
                headers: {
                    'X-CSRF-TO                                .a ttr( 'content' ) 
                 },
                 url: '{{ route('pe.ICFormChecker') }}',
                type: 'POST',
                data: {
                    ap praisalId: appraisalId,
                    ap p
                                aiseeId: appraiseeId,
                },
                success: function  ( response )
                  {
                     console.log( response );
                     if ( respons e.form_ submitted )
                      {
                         $( 'input[type="radio"]' ).prop( 'disabled', true  );
                         $( 'text area' ). prop( 'disabled', true );
                        $( '#confirmation-alert' ).addClass( ' d-none' );
           
                                     $( '#submit-btn' ). text( 'View' ) ;

                        if ( response .hideSignatory  )
                          {
                             $( '#submit-btn' ).r emove();
                          }

                     if ( response.has
                    equest )
                         {
                    
                                if ( response.status ===  'Pendin g' )
                            {
                                  $( '#sendrequest' ).removeClas s( 'btn-primary' ).t ext( '' ).show();
                                $( '#sendreque st' ).addClass ( 'btn-outline-primary' ).text( 'Req uest Sent' ).p rop( 'd isabled', true ) .append( '<i>' ).addClass( 'bi bi-envelope-paper' );
                            } else if ( response.statu s  === 'Approved' ||  
                esponse.status === 'Disapproved' )
                            {
                                 //  Display  feedb ack and appropriate UI for approve d 
        r disapproved re quests
                                   $( '#feedb ack-text' ).t ext( res ponse. feedback );
 
                                   // Check if approver_name and  ap
        roved_at are ava ilable
                                   if (  response.approver_ name &&  respon se.approved_ at )
                                  {
     
                                       const  approverInfo  = ` Appr oved by ${ response.approver_name } on ${ resp ons e.approved_ at  }`;
                                      $( '#additional-i nfo' ).text( appr overInfo ).addClass( 'font-italic' );
                                   }

                           
                 $( '#feedback-container' ).removeClass( 'd- n one' );
                            }
                         }

                        if ( !resp onse.canRequest )
                           {
                  
                  $( '#sendrequest' ).hide();
                              $( '#requestText' ).prop( 'disabled', true );
                            $( '#feedback-containe r' ).addClass(  'd-none' );
                          }
                     } else
                     {
                        if (  !response.hasPermission )
                        {
                              $( '. modal' ) .h
            de();
                            $(  '.co ntent-b ody' ).remove();
                               $( '.content-cont ainer' ).remove();
                        }
                        $ ( '# sendreq uest' ).hide();
                       
                   $( '#requestText' ).pro p(  'di sabled' , true ); 
  
                                          return;
                    }
                },
                er ror: function ( xhr, sta tus, error )
     
                           {
                    // console.log(error);
                 } 
             } );
         }

         $(  document ).on( 'click', '#view-sig-btn', function  ()
        {
            $( '#sign atory_modal' ).modal( 'hide' );
             $( '#ima geModal' ).modal( 'show' );
        } );

         $( document  ).on( 'click', '#esig-close-btn', function ()
         {
            $( ' #image Modal' ).modal( 'hide' );
            $( '#sig natory_modal' ).moda l( 'show' );
        
            } );

        functi on dataURItoBlob ( dataURI )
         {
            var byteString = atob( d ataURI.split( ',' )[1] );
             var mimeString = dataURI.split( ' ,
             )[0].split( ':' )[1].split( ';' )[0];
            va r ab = new ArrayBu ffer( by teStri ng.length );
        
               var ia = new Uint8Array( ab );
            for ( var i = 0; i < byteString.length; i++ )
            {
                 ia[i] = byteString.charCodeAt( i );
            }
            return new Blob( [ab], {
                 t ype: mim eString
   
                 } );
         }

         $( ' #submit-btn' ).on ( 'click', function ()
        {
            var totalWeightedScore = $( '#total-weighted-sc ore' ).val();
             // console.log('Total WeightedScore: ' + totalWeightedScore);
            $( '#IC_table td' ).removeClass( 'is-invali d' );
             $( '#service_area, #comments_area' ).removeClass(
                 'is-invalid' );

             var allRadioChecked = true;
            $( '#IC_table tbody tr' ).each( function ()
             {
        
                    var questionId  = $( this ).find( '. f
                rm-check-input' ).attr( 'id' ).split(
                        '_ '  )[1];
                var anyRadi oChecked = false;

                 $( this ).find( '.form-check-input'  )
            each( function ()
                {
                      if ( $( this ).prop( 'checked'  ) )
                     
            
                         anyRadioChecked = true; 
                   
                  }
                } );

                if  ( !anyRadioChecked )
                {
                     allRadioChecked  = false;
                     $( this ).find ( '.form-check-input' ).clos
            st( 'tr' ).addClass( 
                         'table-danger' );
                } 
            } );

              var s erviceValue  = $( '#service _area' ).val() ;
             var sugge s tionV a
        ue = $( '#comments_area' ).val();
            var  allTextAreasFilled = (  serviceValue.trim() !== '' ) && ( suggestionVa lue
                 .trim() !== '' );

            if ( !allTextAreasFilled )
            {
                $( '#service_area, #comments_area' ).addClass(
                     'i s-invalid' );
            }

             if ( allRadioChec ked && allTextAreasFilled )
            {
                loadSignature();
                 $( '#signatory_modal' ).modal( 'show' );
            } else
            {
                // console.log('Please complete all fields and select all radio buttons.');
            }
        } );

        ////////////////////////// SEND REQUEST  ///////////////////////// /////// 
         $( '#sendrequest' ).click( function ()
        {
              
                $( '#requestText' ).prop( 'disabled', false );

            // Check validity and list  the results
             const  valid ationList = $( '#validation-list' );
            validationList.empty( );

             // Check input elements for validity
              
                const inputElements = $( 'input[disa bled]' ); // Ex clude the textarea
            con st textarea  = $( 'textarea [required]:not(#reques tText )' ); // Exclude the textar ea
                    let invalidFields = [];

            inputElements.each( function ()
             {
                  if ( !this.chec kValid it y() )
                 {
                     in
        alidFields.push( $( this ).attr( 'name' ) );
                }
            } );

            if ( textarea.length > tarea[ 0].cy() )
            {
                invalidFields.push( 'Request field' );
            }

            if ( invalidFields.length > 0 )
            {
                $.each( invalidFields, function ( index, fieldName )
                {
                    validationList.append( '<li>' + fieldName + ' is not answered or invalid.</li>' );
                } );

                $( '#validation-results' ).show();
            } else
            {
                $( '#validation-results' ).hide();
            }

            $( '#request-popup-modal' ).modal( 'show' );
        } );

        $( '#sendReqForm' ).on( 'submit', function ( event )
        {
            var urlParams = new URLSearchParams( window.location.search );
            var appraisalId = urlParams.get( 'appraisal_id' );

            event.preventDefault(); // Prevent the default form submission

            // Collect the form data
            const formData = new FormData( this );
            formData.append( 'appraisal_id', appraisalId );

            // Send the data to the server using AJAX
            $.ajax( {
                url: "{{ route('submitRequest') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( data )
                {
                    // Handle the server response (if needed)
                    $( '#request-popup-modal' ).modal( 'hide' );
                    refreshPage();
                    console.log( data );
                },
                error: function ( error )
                {
                    console.error( 'Error:', error );
                }
            } );
        } );

        $( '#request-popup-modal' ).on( 'hidden.bs.modal', function ()
        {
            // Enable checkboxes and text area when the modal is closed
            $( '#requestText' ).prop( 'disabled', false ).val( '' );
        } );

        function refreshPage ()
        {
            location.reload();
        }

        loadICTable();
        loadTextAreas();
        formChecker();


    } );
</script>
@endsection