<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\DocumentAttachment;
use App\DocumentTransaction;
use DB;
use Auth;
use DateTime;
use App\OnlineUsers;
use App\OnlineDocument;
use App\OnlineDocumentTransaction;
use Hash;

//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

//email
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class NewDocumentController extends Controller
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

    public function newDocument()
    {   
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            //$documents = Document::all();
            $state = '1';
            $documents = db::select(db::raw("select * from document where status= :var order by id desc limit 300 "),['var'=>$state]);

            $transactionType = DB::table('transaction_type')->where('status','=','1')->get();
            $documentType = DB::table('document_type')->where('status','=','1')->get();
            $sourceType = DB::table('source')->where('status','=','1')->get();
            $deliveryMethod = DB::table('delivery_method')->where('status','=','1')->get();
            $offices = DB::table('offices')->where('status','=','1')->where('office_code','!=','REC')->get();

            return view('newDocument',['documents'=>$documents, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'sourceType'=>$sourceType, 'deliveryMethod'=>$deliveryMethod, 'offices'=>$offices]);
        }
        else{
            return redirect('home');
        }
    }

    public function newDocumentSave(Request $request)
    {
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            //check if Barcode was used
            $meron = db::select(db::raw("select count(id)meron from document where barcode= :var"),['var'=>$request->get('barcode')]);
            foreach ($meron as $key => $value) {
                $checker = $value->meron;
            }
            if($checker>=1){
                $request->session()->flash('flash_message_error', ' Barcode already used.');
                return redirect()->back();
            }
            else{
                $dateNow = date('Y-m-d H:i:s');
                $yearNow = date('Y');

                //
                $Record = new Document;
                $Record->barcode = $request->get('barcode');
                $Record->receive_date = $dateNow;

                $tType = $request->get('transactionType');
                $transactionType = DB::table('transaction_type')->where('id','=',$tType)->get();
                foreach ($transactionType as $key => $transactionTypeVal) {
                    $noOfDays = $transactionTypeVal->days;
                }
                
                //compute end date
                $d = new DateTime($dateNow);
                $t = $d->getTimestamp();

                $dChecker = new DateTime($dateNow);
                $tChecker = $d->getTimestamp();

                // loop for X days
                for($i=0; $i<$noOfDays; $i++){

                    // add 1 day to timestamp
                    $addDay = 86400; //secs

                    // get what day it is next day
                    $nextDay = date('w', ($t+$addDay));

                    // if it's Saturday or Sunday get $i-1
                    if($nextDay == 0 || $nextDay == 6) {
                        $i--;
                    }

                    
                    // modify timestamp, add 1 day
                    $t = $t+$addDay;
                   
                    //holiday checker
                    $dChecker->setTimestamp($t);
                    $tChecker = $dChecker->format('Y-m-d');

                    $addHoliday = 0;
                    $holChecker = db::select(db::raw("select count(id)counter from holidays where holiday_date=:var and status='1'"),['var'=>$tChecker]);
                    foreach ($holChecker as $key => $value) {
                        if($value->counter>=1){
                            $addHoliday = 86400;
                        }
                        else{
                            $addHoliday = 0;
                        }
                    }

                    $t = $t+$addHoliday;

                }

                $d->setTimestamp($t);
                $endDate = $d->format( 'Y-m-d H:i:s' );
                //compute end date

                $Record->end_date = $endDate;
                $Record->transaction_type_id = $tType;
                $Record->document_type_id = $request->get('documentType');
                $Record->source_type_id = $request->get('sourceType');
                $Record->office_id = $request->get('sourceLocationId');
                $Record->delivery_method_id = $request->get('deliveryMethod');
                $Record->source_name = $request->get('sourceName');
                $Record->sex = $request->get('sex');
                $Record->contact_no = $request->get('contactNo');
                $Record->email_address = $request->get('emailAddress');
                $Record->subject_matter = $request->get('subjectMatter');
                $Record->documents_linked = $request->get('linkedDocuments');
                $Record->access_code = $request->get('accessCode');
                $Record->document_status = "G"; //on-going
                $Record->user_id = Auth::user()->id;
                $Record->to_mayor = $request->get('toOcm');
                $Record->status = "1";
                $Record->save();

                //email
                if($request->get('sourceType')=="2"){
                    if($request->get('emailAddress')==""){
                        //wala lang
                    }
                    else{

                        //send email
                        $accessCode = $request->get('accessCode');
                        $barcode = $request->get('barcode');
                        $email = $request->get('emailAddress');
                        $source = $request->get('sourceName');
                        try{
                            Mail::to($email)->send(new SendMail($barcode,$accessCode,$source));
                        }
                        catch(\Exception $e){
                            //no action
                        }
                    }
                }

                //     try{
                //         //get id
                //         $qry = db::select(db::raw("select id from document where barcode=:var"),['var'=>$request->get('barcode')]);
                //         foreach ($qry as $key => $value) {
                //             $documentId = $value->id;
                //         }

                // save to dts_online database
                //create online user
                $OnlineRecord = new OnlineUsers;
                $OnlineRecord->name = $request->get('barcode');
                $OnlineRecord->email = $request->get('barcode');
                $OnlineRecord->password = bcrypt($request->get('accessCode'));
                $OnlineRecord->save();

                //         $Record = new OnlineDocument;
                //         $Record->setConnection('mysql2');
                //         $Record->id = $documentId;
                //         $Record->barcode = $request->get('barcode');
                //         $Record->receive_date = $dateNow;
                //         $Record->end_date = $endDate;
                //         $Record->transaction_type_id = $tType;
                //         $Record->document_type_id = $request->get('documentType');
                //         $Record->source_type_id = $request->get('sourceType');
                //         $Record->office_id = $request->get('sourceLocation');
                //         $Record->delivery_method_id = $request->get('deliveryMethod');
                //         $Record->source_name = $request->get('sourceName');
                //         $Record->sex = $request->get('sex');
                //         $Record->contact_no = $request->get('contactNo');
                //         $Record->email_address = $request->get('emailAddress');
                //         $Record->subject_matter = $request->get('subjectMatter');
                //         $Record->documents_linked = $request->get('linkedDocuments');
                //         $Record->access_code = $request->get('accessCode');
                //         $Record->document_status = "G"; //on-going
                //         $Record->user_id = Auth::user()->id;
                //         $Record->to_mayor = $request->get('toOcm');
                //         $Record->status = "1";
                //         $Record->save();

                //     }
                //     catch(\Exception $e){
                //         //die("Could not connect to the database.  Please check your configuration. error:" . $e );
                //         // no action para hindi mag-error
                //     }     

                // }
                // //end email

                $request->session()->flash('flash_message_success', ' Document Added.');
                return redirect()->back();
            }

        }
        else{
            return redirect('home');
        }
    }

    public function documentSearch(Request $request)
    {
        
        $searchBy = $request->get('searchBy');
        $keyword = '%'.$request->get('keyword').'%';
        
        $documents = db::table('document')->where($searchBy,'like',$keyword)->where('status','1')->get();

        $transactionType = DB::table('transaction_type')->where('status','=','1')->get();
        $documentType = DB::table('document_type')->where('status','=','1')->get();
        $sourceType = DB::table('source')->where('status','=','1')->get();
        $deliveryMethod = DB::table('delivery_method')->where('status','=','1')->get();
        $offices = DB::table('offices')->where('status','=','1')->where('office_code','!=','REC')->get();

        return view('newDocument',['documents'=>$documents, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'sourceType'=>$sourceType, 'deliveryMethod'=>$deliveryMethod, 'offices'=>$offices]);

    }

    public function documentDelete(Request $request,$id)
    {
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $delete = db::table('document')->where('id',$id)->update(['status'=>'0']);
            $request->session()->flash('flash_message_success', ' Document Deleted.');
            return redirect()->route('new_document');
        }
        else{
            return redirect('home');
        }
        
    }


    public function documentEdit(Request $request,$id)
    {   
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $document = DB::table('document')->where('status','1')->where('id',$id)->get();
            foreach ($document as $key => $value) {
                $barcode = $value->barcode;
                $transactionTypeId = $value->transaction_type_id;   
                $documentTypeId = $value->document_type_id;
                $sourceTypeId = $value->source_type_id;
                $officeId = $value->office_id;
                $deliveryMethodId = $value->delivery_method_id;
                $sourceName = $value->source_name;
                $sex = $value->sex;
                $contactNo = $value->contact_no;
                $emailAddress = $value->email_address;
                $subjectMatter = $value->subject_matter;
                $accessCode = $value->access_code;
                $documentsLinked = $value->documents_linked;
                $toMayor = $value->to_mayor;
            }
            
            $state = '1';
            $documents = db::select(db::raw("select * from document where status= :var order by id desc limit 300"),['var'=>$state]);

            $transactionTypeChosen = DB::table('transaction_type')->where('id','=',$transactionTypeId)->get();
            $transactionType = DB::table('transaction_type')->where('id','!=',$transactionTypeId)->where('status','1')->get();

            $documentTypeChosen = DB::table('document_type')->where('id',$documentTypeId)->get();
            $documentType = DB::table('document_type')->where('id','!=',$documentTypeId)->where('status','1')->get();

            $sourceTypeChosen = DB::table('source')->where('id',$sourceTypeId)->get();
            $sourceType = DB::table('source')->where('id','!=',$sourceTypeId)->where('status','=','1')->get(); 

            $officesChosenss = DB::table('offices')->where('id','=',$officeId)->get();
            $offices = DB::table('offices')->where('status','=','1')->where('office_code','!=','REC')->where('id','!=',$officeId)->get();

            $deliveryMethodChosen = DB::table('delivery_method')->where('id',$deliveryMethodId)->where('status','=','1')->get();
            $deliveryMethod = DB::table('delivery_method')->where('status','=','1')->where('id','!=',$deliveryMethodId)->get();

            return view('editDocument',['documents'=>$documents, 'transactionTypeChosen'=>$transactionTypeChosen, 'transactionType'=>$transactionType, 'documentTypeChosen'=>$documentTypeChosen, 'documentType'=>$documentType, 'sourceTypeChosen'=>$sourceTypeChosen, 'sourceType'=>$sourceType, 'deliveryMethodChosen'=>$deliveryMethodChosen, 'deliveryMethod'=>$deliveryMethod, 'officesChosenss'=>$officesChosenss, 'offices'=>$offices, 'barcode'=>$barcode,'sourceTypeId'=>$sourceTypeId, 'sourceName'=>$sourceName, 'sex'=>$sex, 'contactNo'=>$contactNo, 'emailAddress'=>$emailAddress, 'subjectMatter'=>$subjectMatter, 'documentsLinked'=>$documentsLinked, 'accessCode'=>$accessCode, 'toMayor'=>$toMayor, 'id'=>$id, 'officeId'=>$officeId]);
        }
        else{
            return redirect('home');
        }
    }
    

    public function documentEditSave(Request $request,$id)
    {
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $record = Document::find($id);
            $record->barcode = strtoupper($request->get('barcode'));
            $record->transaction_type_id = $request->get('transactionType');

            $docDate = $record->receive_date;
            $transactionType = DB::table('transaction_type')->where('id','=',$record->transaction_type_id)->get();
            foreach ($transactionType as $key => $transactionTypeVal) {
                $noOfDays = $transactionTypeVal->days;
            }
            
            //compute end date
            $d = new DateTime($docDate);
            $t = $d->getTimestamp();

            $dChecker = new DateTime($docDate);
            $tChecker = $d->getTimestamp();

            // loop for X days
            for($i=0; $i<$noOfDays; $i++){

                // add 1 day to timestamp
                $addDay = 86400;

                // get what day it is next day
                $nextDay = date('w', ($t+$addDay));

                // if it's Saturday or Sunday get $i-1
                if($nextDay == 0 || $nextDay == 6) {
                    $i--;
                }

                // modify timestamp, add 1 day
                $t = $t+$addDay;

                //holiday checker
                $dChecker->setTimestamp($t);
                $tChecker = $dChecker->format('Y-m-d');

                $addHoliday = 0;
                $holChecker = db::select(db::raw("select count(id)counter from holidays where holiday_date=:var and status='1'"),['var'=>$tChecker]);
                foreach ($holChecker as $key => $value) {
                    if($value->counter>=1){
                        $addHoliday = 86400;
                    }
                    else{
                        $addHoliday = 0;
                    }
                }

                $t = $t+$addHoliday;
            }

            $d->setTimestamp($t);
            $endDate = $d->format( 'Y-m-d H:i:s' );
            //compute end date

            $record->end_date = $endDate;

            $record->document_type_id = $request->get('documentType');
            $record->source_type_id = $request->get('sourceType');
            $record->office_id = $request->get('sourceLocationId');
            $record->delivery_method_id = $request->get('deliveryMethod');
            $record->source_name = $request->get('sourceName');
            $record->sex = $request->get('sex');
            $record->contact_no = $request->get('contactNo');
            $record->email_address = $request->get('emailAddress');
            $record->subject_matter = $request->get('subjectMatter');
            $record->documents_linked = $request->get('linkedDocuments');
            $record->to_mayor = $request->get('toOcm');
            $record->save();

            $request->session()->flash('flash_message_success', ' Document Updated.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }

    public function documentAttachment(Request $request,$id)
    {
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $document_detail = DB::table('document')->where('id','=',$id)->get();
            foreach ($document_detail as $key => $value) {
                $parentId = $value->id;
                $docBarcode = $value->barcode;
            }

            $attached = DB::table('document_attachments')->where('parent_id','=',$id)->where('status','=','1')->get();

            return view('documentAttachment',['parentId'=>$parentId, 'docBarcode'=>$docBarcode, 'attached'=>$attached]);
        }
        else{
            return redirect('home');
        }
    }



    public function documentAttachmentSave(Request $request){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            // ini_set("memory_limit", "100M");
            // ini_set('post_max_size', '100M');
            // ini_set('upload_max_filesize', '100M');

            $uploadFile = $request->file('pdfFile');
            $uploadFileExtension = $request->file('pdfFile')->extension();
            $barcode = $request->get('barcode');

            if($uploadFileExtension=="pdf"){
               
                function randomString($length = 20) {
                    $str = "";
                    $characters = array_merge(range('A','Z'), range('a','z'), range('1','9'));
                    $max = count($characters) - 1;
                    for ($i = 0; $i < $length; $i++) {
                        $rand = mt_rand(0, $max);
                        $str .= $characters[$rand];
                    }
                    return $str.date('YmdHis').$str;
                }
                $randomFileName = $barcode.randomString().'.pdf';

                $uploadFile->move(base_path('\storage\app\public\documents'),$randomFileName);
                $RecordAttach = new DocumentAttachment;
                $RecordAttach->parent_id = $request->get('parentId');
                $RecordAttach->document_name = $uploadFile->getClientOriginalName();
                $RecordAttach->token_name = $randomFileName;
                $RecordAttach->status = "1";
                $RecordAttach->save();

                $request->session()->flash('flash_message_success', ' File Uploaded.');
                
            }
            else{
                $request->session()->flash('flash_message_error', ' Upload PDF file.');
                
            }
            return redirect()->back();  
        }
        else{
            return redirect('home');
        }
    }

    public function documentDownload(Request $request, $id){

        $docDetails = DB::select(DB::raw("select * from document a, document_attachments b where b.parent_id=a.id and b.id=:var1"),['var1'=>$id]);
        foreach ($docDetails as $key => $docDetailsVal) {
            $fileName = $docDetailsVal->token_name;
        }

        return response()->file(storage_path("app/public/documents/".$fileName));
    }  

    public function documentAttachmentDelete(Request $request, $id){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            //unlink('/path/to/dir/filename');
            $docAttachment = db::select(db::raw("select * from document_attachments where id=:var"),['var'=>$id]);
            foreach ($docAttachment as $key => $dAValue) {
                $fileName = $dAValue->token_name;
            }

            $myFile = '../storage/app/public/documents/'.$fileName;
            //echo $myFile;
            File::delete($myFile);

        
            $del=DocumentAttachment::where('id',$id)->delete();
            return redirect()->back(); 
        }
        else{
            return redirect('home');
        } 
    }

    public function documentRoute(Request $request, $id){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $documentDetails = db::select(db::raw("select * from document where id=:var"),['var'=>$id]);
            foreach ($documentDetails as $key => $value) {
                $barcode = $value->barcode;
            }

            $listOfOffices = db::select(db::raw("select * from offices where status=:var order by office_code asc"),['var'=>'1']); //and id!='1'
            $listRoutedOffice = db::select(db::raw("select * from document_transaction where document_id=:var and status='1' order by id asc"),['var'=>$id]);

            return view('documentRoute',['barcode'=>$barcode, 'listOfOffices'=>$listOfOffices, 'id'=>$id, 'listRoutedOffice'=>$listRoutedOffice]);
        }
        else{
            return redirect('home');
        } 
    }

    public function documentRouteOffice(Request $request, $id, $officeCode){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $userId = Auth::user()->id;
            $officeId = Auth::user()->office_id;

            $userDetail = DB::select(DB::raw("select * from users where id=:var"),['var'=>$userId]);
            foreach ($userDetail as $key => $userDetailVal) {
                $userName = $userDetailVal->name;
            }

            $dateTimeNow = date('Y-m-d H:i:s');

            $docDetail = DB::select(DB::raw("select *,TIMESTAMPDIFF(minute,receive_date,'$dateTimeNow')total_minute from document where id=:var"),['var'=>$id]);
            foreach ($docDetail as $key => $docDetailVal) {
                $receiveDate = $docDetailVal->receive_date;
                $barcode = $docDetailVal->barcode;
                $sourceTypeId = $docDetailVal->source_type_id;

                $documentTypeId = $docDetailVal->document_type_id;
                $totalMinute = $docDetailVal->total_minute;
            }       

            //get data from office_performance table
            $performance = db::select(db::raw("select * from office_performance where office_id=:var1 and document_code_id=:var2"),['var1'=>$officeId, 'var2'=>$documentTypeId]);
            foreach ($performance as $key => $value) {
                $totalOfficeMinute = $value->office_time;
            }


            //add checker
            $check = DB::select(DB::raw("select count(id)counter from document_transaction where document_id= :var1 and route_office_code= :var2"),['var1'=>$id, 'var2'=>$officeCode]);
            foreach ($check as $key => $checkVal) {
                $checkCounter = $checkVal->counter;
            }

            if($checkCounter>=1){
                $request->session()->flash('flash_message_warning', ' Already routed.');
            }
            else{
                $Record = new DocumentTransaction;
                $Record->document_id = $id;
                $Record->sequence = "1";
                //$Record->transit_date_time = "0000-00-00 00:00:00";
                $Record->office_code = "REC";
                $Record->receive_person = $userName;
                $Record->receive_date_time = $receiveDate;
                $Record->receive_action = "New Document Trail";
                $Record->route_office_code = $officeCode;
                $Record->release_person = $userName; //$userId;
                $Record->release_date_time = $dateTimeNow;
                $Record->release_action = "-";
                $Record->remarks = "-";
                $Record->current_action = "REL";
                $Record->status = "1";

                //transaction status
                if($totalMinute>$totalOfficeMinute){
                    $Record->transaction_status = "D"; //delinquent
                }
                else{
                    $Record->transaction_status = "O"; //on-time
                }

                $Record->save();

               
            }
            
            return redirect()->back();  
        }
        else{
            return redirect('home');
        }
    }

    public function documentRouteOfficeAll(Request $request, $id){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){

            $userId = Auth::user()->id;
            $officeId = Auth::user()->office_id;

            $userDetail = DB::select(DB::raw("select * from users where id=:var"),['var'=>$userId]);
            foreach ($userDetail as $key => $userDetailVal) {
                $userName = $userDetailVal->name;
            }

            $dateTimeNow = date('Y-m-d H:i:s');

            $docDetail = DB::select(DB::raw("select *,TIMESTAMPDIFF(minute,receive_date,'$dateTimeNow')total_minute from document where id=:var"),['var'=>$id]);
            foreach ($docDetail as $key => $docDetailVal) {
                $receiveDate = $docDetailVal->receive_date;
                $barcode = $docDetailVal->barcode;
                $sourceTypeId = $docDetailVal->source_type_id;

                $documentTypeId = $docDetailVal->document_type_id;
                $totalMinute = $docDetailVal->total_minute;
            }  

            //get data from office_performance table
            $performance = db::select(db::raw("select * from office_performance where office_id=:var1 and document_code_id=:var2"),['var1'=>$officeId, 'var2'=>$documentTypeId]);
            foreach ($performance as $key => $value) {
                $totalOfficeMinute = $value->office_time;
            }


            DocumentTransaction::where('document_id',$id)->delete();

            $qryOffices = db::select(db::raw("select * from offices where status=:var and id!='1'"),['var'=>'1']);
            foreach ($qryOffices as $key => $value) {
                $Record = new DocumentTransaction;
                $Record->document_id = $id;
                $Record->sequence = "1";
                //$Record->transit_date_time = "0000-00-00 00:00:00";
                $Record->office_code = "REC";
                $Record->receive_person = $userName;
                $Record->receive_date_time = $receiveDate;
                $Record->receive_action = "New Document Trail";
                $Record->route_office_code = $value->office_code;

                $Record->release_person = $userName;
                $Record->release_date_time = $dateTimeNow;
                $Record->release_action = "-";
                $Record->remarks = "-";
                $Record->current_action = "REL";
                $Record->status = "1";

                //transaction status
                if($totalMinute>$totalOfficeMinute){
                    $Record->transaction_status = "D"; //delinquent
                }
                else{
                    $Record->transaction_status = "O"; //on-time
                }


                $Record->save();
            }

            return redirect()->back();  

        }
        else{
            return redirect('home');
        }
    }

    public function documentRouteOfficeRemove(Request $request, $id, $officeCode){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            db::select(db::raw("delete from document_transaction where route_office_code= :var1 and document_id= :var2"),['var1'=>$officeCode, 'var2'=>$id]);

            try{
                db::connection('mysql2')->select(db::raw("delete from online_document_transaction where route_office_code= :var1 and document_id= :var2"),['var1'=>$officeCode, 'var2'=>$id]);
            }
            catch(\Exception $e){
                // no action para hindi mag-error
            }     

            return redirect()->back();      
        }
        else{
            return redirect('home');
        }    
    }

    public function documentRouteOfficeRemoveAll(Request $request, $id){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            db::select(db::raw("delete from document_transaction where document_id= :var2"),['var2'=>$id]);

            try{
                db::connection('mysql2')->select(db::raw("delete from online_document_transaction where document_id= :var2"),['var2'=>$id]);
            }
            catch(\Exception $e){
                // no action para hindi mag-error
            }  

            return redirect()->back();   
        }
        else{
            return redirect('home');
        }   
    }

}
