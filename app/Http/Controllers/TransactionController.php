<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Document;
use App\DocumentAttachment;
use App\DocumentTransaction;

use App\Offices;
use App\OnlineDocument;
use App\OnlineDocumentTransaction;
use DB;
use Auth;
use DateTime;
use Hash;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function transaction()
    {   

        

    	return view('transaction');
    }


    public function transactionResult(Request $request){

    	$userId = Auth::user()->id;
    	$barcode = $request->get('barcodeSearch');

    	$userOffice = db::select(db::raw("select *,(a.office_id)aOfficeId from users a, offices b where a.office_id=b.id and a.id=:var"),['var'=>$userId]);
    	foreach ($userOffice as $key => $value) {
    		$officeCode = $value->office_code;
            $userOfficeId = $value->aOfficeId;
            $userAccess = $value->access;
    	}

    	$currentAction = "";
    	$documentTransactionCurrentStat = db::select(db::raw("select *,(a.id)docId,(b.id)docTransId from document a, document_transaction b, document_type c, delivery_method d, transaction_type e, source f where a.id=b.document_id and (b.route_office_code=:var1 or b.office_code=:var3)  and b.status='1' and a.barcode=:var2 and c.id=a.document_type_id and d.id=a.delivery_method_id and e.id=a.transaction_type_id and f.id=a.source_type_id"),['var1'=>$officeCode, 'var2'=>$barcode, 'var3'=>$officeCode]);

    	foreach ($documentTransactionCurrentStat as $key => $details) {
            $documentId = $details->docId;
    		$currentAction = $details->current_action;
    		$barcode = $details->barcode;
            $transactionType = $details->transaction_type;
            $documentType = $details->document_type;
            $source = $details->source;
            $sourceName = $details->source_name;
            $officeId = $details->office_id;
            $documentTransactionId = $details->docTransId;
            $fromOffice = $details->office_code; 
            $toOffice = $details->route_office_code;

            if($officeId==""){
                $officeName = "";
            }
            else{ 
                $office = Offices::find($officeId);
                $officeName = $office->office_name;
            }
            $subjectMatter = $details->subject_matter;

    	}
        

    	if($currentAction=="REL" && $toOffice==$officeCode){ //REL
    		//to receive

    		return view('transactionReceive',['barcode'=>$barcode, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'source'=>$source, 'sourceName'=>$sourceName, 'officeName'=>$officeName, 'subjectMatter'=>$subjectMatter, 'officeCode'=>$officeCode, 'documentId'=>$documentId, 'documentTransactionId'=>$documentTransactionId]);
    	}
    	else if($currentAction=="REC" && $fromOffice==$officeCode){ //REC
    		//release 
            $offices = db::select(db::raw("select * from offices where status=:var1 and id!=:var2 order by office_code asc"),['var1'=>'1','var2'=>$userOfficeId]);
            if($userAccess=="R"){
                return view('transactionReleaseRecords',['barcode'=>$barcode, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'source'=>$source, 'sourceName'=>$sourceName, 'officeName'=>$officeName, 'subjectMatter'=>$subjectMatter, 'officeCode'=>$officeCode, 'documentId'=>$documentId, 'userAccess'=>$userAccess, 'offices'=>$offices, 'documentTransactionId'=>$documentTransactionId]);
            }
            else{
                return view('transactionRelease',['barcode'=>$barcode, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'source'=>$source, 'sourceName'=>$sourceName, 'officeName'=>$officeName, 'subjectMatter'=>$subjectMatter, 'officeCode'=>$officeCode, 'documentId'=>$documentId, 'userAccess'=>$userAccess, 'offices'=>$offices, 'documentTransactionId'=>$documentTransactionId]);
            }
    		
    	}
    	else{
            
    		$request->session()->flash('flash_message_error', ' No document routed in this office.');
        	//return redirect()->back();
            return redirect('transaction');
    	}

    }

    public function transactionReceive(Request $request){
        $barcode = $request->get('barcode');
        $officeCode = $request->get('userOfficeCode');  
        $dateNow = date('Y-m-d H:i:s');
        $documentId = $request->get('documentId');
        $userId = Auth::user()->id;
        $userDetail = DB::select(DB::raw("select * from users where id=:var"),['var'=>$userId]);

        foreach ($userDetail as $key => $userDetailVal) {
            $uName = $userDetailVal->name;
        }
        $action = $request->get('action');
       
        //get latest sequence
        $getSequence = db::select(db::raw("select distinct(b.sequence)ls from document a, document_transaction b where a.barcode=:var and a.id=b.document_id order by sequence desc limit 1"),['var'=>$barcode]);
        foreach ($getSequence as $key => $value) {
            $latestSequence = $value->ls+1;
        }

        //update document_transaction
        $getDocTrans = db::select(db::raw("select b.id,a.source_type_id from document a, document_transaction b where a.barcode=:var and a.id=b.document_id and b.route_office_code=:var2 and b.current_action='REL' and b.status='1'"),['var'=>$barcode, 'var2'=>$officeCode]);
        foreach ($getDocTrans as $key => $value) {
            $docTransactionId = $value->id;
            $sourceId = $value->source_type_id;
        }

        $record = DocumentTransaction::find($docTransactionId);
        $record->current_action = "";
        $record->transit_date_time = $dateNow;
        $record->status = "0";
        $record->save();

        $newRecord = new DocumentTransaction;
        $newRecord->document_id = $documentId;
        $newRecord->sequence = $latestSequence;
        //$newRecord->transit_date_time = "0000-00-00 00:00:00";
        $newRecord->office_code = $officeCode;
        $newRecord->receive_person = $uName;
        $newRecord->receive_date_time = $dateNow;
        $newRecord->receive_action = $action;
        $newRecord->route_office_code = "";
        $newRecord->release_person = "";
        //$newRecord->release_date_time = "0000-00-00 00:00:00";
        $newRecord->release_action = "-";
        $newRecord->remarks = "-";
        $newRecord->current_action = "REC";
        $newRecord->status = "1";
        $newRecord->save();

        $request->session()->flash('flash_message_success', ' Document successfully received.');
        //return redirect()->back();
        return redirect('transaction');

    }

    public function transactionRelease(Request $request){
        $documentTransactionId = $request->get('documentTransactionId');
        $routeOffice = $request->get('route');
        $action = $request->get('action');
        $remarks = $request->get('remarks');

        $userId = Auth::user()->id;
        $userOffice = db::select(db::raw("select *,(a.office_id)aOfficeId from users a, offices b where a.office_id=b.id and a.id=:var"),['var'=>$userId]);
        foreach ($userOffice as $key => $value) {
            $fullName = $value->name;
        }

        $dateNow = date('Y-m-d H:i:s');
        

        $rec = db::select(db::raw("select a.id,source_type_id,b.office_code,TIMESTAMPDIFF(minute,b.receive_date_time,'$dateNow')total_minute,a.document_type_id,(c.id)cid,(b.receive_date_time)rdatetime from document a, document_transaction b, offices c where c.office_code=b.office_code and a.id=b.document_id and b.id=:var"),['var'=>$documentTransactionId]);
        foreach ($rec as $key => $value) {
            $sourceId = $value->source_type_id;
            $documentId = $value->id;
            $officeCode = $value->office_code;
            $officeId = $value->cid;

            $documentTypeId = $value->document_type_id;

            //weekends start
            $start = new DateTime($value->rdatetime);
            $end = new DateTime($dateNow);
            $days = $start->diff($end, true)->days;

            $saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
            $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

            $minutesWeekends = ($saturdays+$sundays)*1440;
            //weekends end

            //holiday start
            $dateRange1 = explode(' ',$value->rdatetime);
            $dateRange2 = explode(' ',$dateNow);

            $holidayRangeFrom = $dateRange1[0];
            $holidayRangeTo = $dateRange2[0];

            $qryHoliday = db::select(db::raw("select (count(id)*1440)minutesHolidays from holidays where status='1' and holiday_date between '$holidayRangeFrom' and '$holidayRangeTo'"));
            foreach($qryHoliday as $val){
                $totalMinuteHoliday = $val->minutesHolidays;
            }
            //holiday end

            $totalMinute = $value->total_minute-$totalMinuteHoliday-$minutesWeekends;
        }

        //get data from office_performance table
        $performance = db::select(db::raw("select * from office_performance where office_id=:var1 and document_code_id=:var2"),['var1'=>$officeId, 'var2'=>$documentTypeId]);
        foreach ($performance as $key => $value) {
            $totalOfficeMinute = $value->office_time;
        }

        $record = DocumentTransaction::find($documentTransactionId);
        $record->route_office_code = $routeOffice;
        $record->release_person = $fullName;
        $record->release_date_time = $dateNow;
        $record->release_action = $action;
        $record->remarks = $remarks;
        $record->current_action = "REL";

        if($totalMinute>$totalOfficeMinute){
            $record->transaction_status = "D"; //delinquent
        }
        else{
            $record->transaction_status = "O"; //on-time
        }
        
        $record->save();


        $request->session()->flash('flash_message_success', ' Document successfully released.');
        
        //return redirect()->back();
        return redirect('transaction');
    }

    public function transactionReleaseRecords(Request $request){
        $currentDocTransactionId = $request->get('documentTransactionId');
        $action = $request->get('action');
        $remarks = $request->get('remarks');

        $dateNow = date('Y-m-d H:i:s');
        $userId = Auth::user()->id;
        $userOfficeId = Auth::user()->office_id;

        $userOffice = db::select(db::raw("select *,(a.office_id)aOfficeId from users a, offices b where a.office_id=b.id and a.id=:var"),['var'=>$userId]);
        foreach ($userOffice as $key => $value) {
            $fullName = $value->name;
        }

        //get details before deleting
        $detail = db::select(db::raw("select *,TIMESTAMPDIFF(minute,b.receive_date_time,'$dateNow')total_minute,(b.receive_date_time)rdatetime from document a, document_transaction b where b.id=:var and b.document_id=a.id"),['var'=>$currentDocTransactionId]);
        foreach ($detail as $key => $detailValue) {
            $sourceId = $detailValue->source_type_id;
            $documentId = $detailValue->document_id;
            $sequence = $detailValue->sequence;
            $officeCode = $detailValue->office_code;
            $receivePerson = $detailValue->receive_person;
            $receiveDateTime = $detailValue->receive_date_time;
            $receiveAction = $detailValue->receive_action;
            $documentTypeId = $detailValue->document_type_id;


            //weekends start
            $start = new DateTime($detailValue->rdatetime);
            $end = new DateTime($dateNow);
            $days = $start->diff($end, true)->days;

            $saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
            $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

            $minutesWeekends = ($saturdays+$sundays)*1440;
            //weekends end

            //holiday start
            $dateRange1 = explode(' ',$detailValue->rdatetime);
            $dateRange2 = explode(' ',$dateNow);

            $holidayRangeFrom = $dateRange1[0];
            $holidayRangeTo = $dateRange2[0];

            $qryHoliday = db::select(db::raw("select (count(id)*1440)minutesHolidays from holidays where status='1' and (DAYOFWEEK(holiday_date)!=1 or DAYOFWEEK(holiday_date)!=7) and holiday_date between '$holidayRangeFrom' and '$holidayRangeTo'"));
            foreach($qryHoliday as $val){
                $totalMinuteHoliday = $val->minutesHolidays;
            }
            //holiday end

            $totalMinute = $detailValue->total_minute-$totalMinuteHoliday-$minutesWeekends;
        }

        //get data from office_performance table
        $performance = db::select(db::raw("select * from office_performance where office_id=:var1 and document_code_id=:var2"),['var1'=>$userOfficeId, 'var2'=>$documentTypeId]);
        foreach ($performance as $key => $value) {
             $totalOfficeMinute = $value->office_time;
        }

        // delete document transaction
        $deleteTransaction = db::select(db::raw("delete from document_transaction where id= :var1"),['var1'=>$currentDocTransactionId]);

        $qryOffices = db::select(db::raw("select * from offices where status=:var"),['var'=>'1']);
        foreach ($qryOffices as $key => $value){
            if(Input::get('checkOffice'.$value->id,false)){
                $docTransaction = new DocumentTransaction;
                $docTransaction->document_id = $documentId;
                $docTransaction->sequence = $sequence;
                //$docTransaction->transit_date_time = $dateNow;
                $docTransaction->office_code = $officeCode;
                $docTransaction->receive_person = $receivePerson;
                $docTransaction->receive_date_time = $receiveDateTime;
                $docTransaction->receive_action = $receiveAction;
                $docTransaction->route_office_code = $request->get('checkOffice'.$value->id);
                $docTransaction->release_person = $fullName;
                $docTransaction->release_date_time = $dateNow;
                $docTransaction->release_action = $action;
                $docTransaction->remarks = $remarks;
                $docTransaction->current_action = "REL";

                // if($totalMinute>$totalOfficeMinute){
                //     $docTransaction->transaction_status = "D"; //delinquent
                // }
                // else{
                //     $docTransaction->transaction_status = "O"; //on-time
                // }

                if($totalMinute==0 ||$totalMinute==""){
                    
                    $docTransaction->transaction_status = "O"; //on-time
                }
                else{
                    if($totalMinute>$totalOfficeMinute){
                       $docTransaction->transaction_status = "D"; //delinquent
                    }
                    else{
                        $docTransaction->transaction_status = "O"; //on-time
                    }
                }

                $docTransaction->status = "1";
                $docTransaction->save();

            }
            else{
                //n/a
            }
        }



        $request->session()->flash('flash_message_success', ' Document successfully released.');
        //return redirect()->back();
        return redirect('transaction');
    }

    public function transactionEnd($id){
        $details = db::select(db::raw("select * from document a, document_transaction b where a.id=b.document_id and b.id=:var"),['var'=>$id]);
        foreach ($details as $key => $value) {
            $barcode = $value->barcode;
        }

        $userId = Auth::user()->id;
        
        $userOffice = db::select(db::raw("select *,(a.office_id)aOfficeId from users a, offices b where a.office_id=b.id and a.id=:var"),['var'=>$userId]);
        foreach ($userOffice as $key => $value) {
            $officeCode = $value->office_code;
            $userOfficeId = $value->aOfficeId;
            $userAccess = $value->access;
        }

        $currentAction = "";
        $documentTransactionCurrentStat = db::select(db::raw("select *,(a.id)docId,(b.id)docTransId from document a, document_transaction b, document_type c, delivery_method d, transaction_type e, source f where a.id=b.document_id and (b.route_office_code=:var1 or b.office_code=:var3)  and b.status='1' and a.barcode=:var2 and c.id=a.document_type_id and d.id=a.delivery_method_id and e.id=a.transaction_type_id and f.id=a.source_type_id"),['var1'=>$officeCode, 'var2'=>$barcode, 'var3'=>$officeCode]);

        foreach ($documentTransactionCurrentStat as $key => $details) {
            $documentId = $details->docId;
            $currentAction = $details->current_action;
            $barcode = $details->barcode;
            $transactionType = $details->transaction_type;
            $documentType = $details->document_type;
            $source = $details->source;
            $sourceName = $details->source_name;
            $officeId = $details->office_id;
            $documentTransactionId = $details->docTransId;
            $fromOffice = $details->office_code;
            $toOffice = $details->route_office_code;
            $latestSequence = $details->sequence;

            if($officeId==""){
                $officeName = "";
            }
            else{ 
                $office = Offices::find($officeId);
                $officeName = $office->office_name;
            }
            $subjectMatter = $details->subject_matter;
        }

        return view('transactionEnd',['barcode'=>$barcode, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'source'=>$source, 'sourceName'=>$sourceName, 'officeName'=>$officeName, 'subjectMatter'=>$subjectMatter, 'officeCode'=>$officeCode, 'documentId'=>$documentId, 'userAccess'=>$userAccess, 'documentTransactionId'=>$documentTransactionId, 'latestSequence'=>$latestSequence]);
    }

    public function transactionEndConfirm(Request $request){
        $docTransId = $request->get('documentTransactionId');
        $docId = $request->get('documentId');
        $officeCode = $request->get('userOfficeCode');
        $action = $request->get('action');
        $remarks = $request->get('remarks');
        $userId = Auth::user()->id;
        
        $userOffice = db::select(db::raw("select *,(a.office_id)aOfficeId from users a, offices b where a.office_id=b.id and a.id=:var"),['var'=>$userId]);
        foreach ($userOffice as $key => $value) {
           
            $fullName = $value->name;
        }

        //sequence here
        $latestSequence = $request->get('latestSequence');

        $dateNow = date('Y-m-d H:i:s');
        $currentPassword = $request->get('password');
        $au = Hash::check($currentPassword, Auth::user()->password, []);

        if($au=='1'){ //correct password

            $recDt = DocumentTransaction::find($docTransId);
            $routed = $recDt->route_office_code;

            if($routed==""){
                $record = DocumentTransaction::find($docTransId);
                $record->transit_date_time = $dateNow;
                $record->release_date_time = $dateNow;
                $record->current_action = "END";
                $record->release_person =  $fullName;
                $record->release_action = "End Transaction";
                $record->remarks = $action.' '.$remarks; //"-";
                $record->status = "0";

                //add transaction status
                $userOfficeId = Auth::user()->office_id;
                $detail = db::select(db::raw("select *,TIMESTAMPDIFF(minute,b.receive_date_time,'$dateNow')total_minute,(b.receive_date_time)rdatetime from document a, document_transaction b where b.id=:var and b.document_id=a.id"),['var'=>$docTransId]);

                foreach ($detail as $key => $detailValue) {
                    $documentTypeId = $detailValue->document_type_id;

                    //weekends start
                    $start = new DateTime($detailValue->rdatetime);
                    $end = new DateTime($dateNow);
                    $days = $start->diff($end, true)->days;

                    $saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
                    $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

                    $minutesWeekends = ($saturdays+$sundays)*1440;
                    //weekends end

                    //holiday start
                    $dateRange1 = explode(' ',$detailValue->rdatetime);
                    $dateRange2 = explode(' ',$dateNow);

                    $holidayRangeFrom = $dateRange1[0];
                    $holidayRangeTo = $dateRange2[0];

                    $qryHoliday = db::select(db::raw("select (count(id)*1440)minutesHolidays from holidays where status='1' and (DAYOFWEEK(holiday_date)!=1 or DAYOFWEEK(holiday_date)!=7) and holiday_date between '$holidayRangeFrom' and '$holidayRangeTo'"));
                    foreach($qryHoliday as $val){
                        $totalMinuteHoliday = $val->minutesHolidays;
                    }
                    //holiday end

                    $totalMinute = $detailValue->total_minute-$totalMinuteHoliday-$minutesWeekends;

                    //42
                }

                //get data from office_performance table
                $performance = db::select(db::raw("select * from office_performance where office_id=:var1 and document_code_id=:var2"),['var1'=>$userOfficeId, 'var2'=>$documentTypeId]);
                foreach ($performance as $key => $value) {
                   
                    $totalOfficeMinute = $value->office_time;
                    //7200
                }
                if($totalMinute==0 ||$totalMinute==""){
                    $record->transaction_status = "O"; //on-time
                }
                else{
                    if($totalMinute>$totalOfficeMinute){
                        $record->transaction_status = "D"; //delinquent
                    }
                    else{
                        $record->transaction_status = "O"; //on-time
                    }
                }

                                
                $record->save();
            }
            else{
                $record = DocumentTransaction::find($docTransId);
                $record->current_action = "";
                $record->transit_date_time = $dateNow;
                $record->release_date_time = $dateNow;
                $record->status = "0";
                $record->save();

                $newRecord = new DocumentTransaction;
                $newRecord->document_id = $docId;
                $newRecord->sequence = $latestSequence+1;
                $newRecord->transit_date_time = $dateNow;
                $newRecord->office_code = $officeCode;
                $newRecord->receive_person = $fullName;
                $newRecord->receive_date_time = $dateNow;
                $newRecord->receive_action = $action;
                $newRecord->route_office_code = "";
                $newRecord->release_person = $fullName; //"";
                $newRecord->release_date_time = $dateNow;
                $newRecord->release_action = "End Transaction";
                $newRecord->remarks = $action.' '.$remarks; //"-";
                $newRecord->current_action = "END";
                $newRecord->status = "0";


                //add transaction status
                $userOfficeId = Auth::user()->office_id;
                $detail = db::select(db::raw("select *,TIMESTAMPDIFF(minute,b.receive_date_time,'$dateNow')total_minute,(b.receive_date_time)rdatetime from document a, document_transaction b where b.id=:var and b.document_id=a.id"),['var'=>$docTransId]);
                foreach ($detail as $key => $detailValue) {
                    $documentTypeId = $detailValue->document_type_id;

                    //weekends start
                    $start = new DateTime($detailValue->rdatetime);
                    $end = new DateTime($dateNow);
                    $days = $start->diff($end, true)->days;

                    $saturdays = intval($days / 6) + ($start->format('N') + $days % 6 >= 6);
                    $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

                    $minutesWeekends = ($saturdays+$sundays)*1440;
                    //weekends end

                    //holiday start
                    $dateRange1 = explode(' ',$detailValue->rdatetime);
                    $dateRange2 = explode(' ',$dateNow);

                    $holidayRangeFrom = $dateRange1[0];
                    $holidayRangeTo = $dateRange2[0];

                    $qryHoliday = db::select(db::raw("select (count(id)*1440)minutesHolidays from holidays where status='1' and holiday_date between '$holidayRangeFrom' and '$holidayRangeTo'"));
                    foreach($qryHoliday as $val){
                        $totalMinuteHoliday = $val->minutesHolidays;
                    }
                    //holiday end

                    $totalMinute = $detailValue->total_minute-$totalMinuteHoliday-$minutesWeekends;
                }

                //get data from office_performance table
                $performance = db::select(db::raw("select * from office_performance where office_id=:var1 and document_code_id=:var2"),['var1'=>$userOfficeId, 'var2'=>$documentTypeId]);
                foreach ($performance as $key => $value) {
                   
                    $totalOfficeMinute = $value->office_time;
                }
                
                

                if($totalMinute==0 ||$totalMinute==""){
                    $record->transaction_status = "O"; //on-time
                }
                else{
                    if($totalMinute>$totalOfficeMinute){
                        $record->transaction_status = "D"; //delinquent
                    }
                    else{
                        $record->transaction_status = "O"; //on-time
                    }
                }

                $newRecord->save();

            }


            //check if transaction is completed
            $check = DocumentTransaction::where('document_id',$docId)->where('status','1')->get();
            $counterCheck = $check->count();

            if($counterCheck>=1){
                //no action
            }
            else{
                //
                $recUpdate = Document::find($docId);

                $recUpdate->transaction_end_date = $dateNow;
                $recUpdate->save();

                $computeMin = db::select(db::raw("select timestampdiff(minute,receive_date,end_date)minutes, timestampdiff(minute,receive_date,transaction_end_date)minutes2 from document where id=:var"),['var'=>$docId]);

                foreach ($computeMin as $key => $value) {
                    if($value->minutes2<=$value->minutes){
                        //ontime
                        $docStatus = Document::find($docId);
                        $docStatus->document_status = "O";
                        $docStatus->save();
                    }
                    else{
                        //delayed
                        $docStatus = Document::find($docId);
                        $docStatus->document_status = "D";
                        $docStatus->save();
                    }
                }

            }
            
            $request->session()->flash('flash_message_success', ' Document transaction ended.');
            return view('transaction');

        }
        else{   //wrong password
            $request->session()->flash('flash_message_error', ' Incorrect Password.');
            //return redirect()->back();
            return redirect('transaction');
        }
    }



}
