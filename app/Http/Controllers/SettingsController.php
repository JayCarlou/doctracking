<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offices;
use App\Document;
use App\DocumentType;
use App\DeliveryMethod;
use App\TransactionType;
use App\DocumentTransaction;
use App\User;
use App\Holidays;

use DB;
use App\Http\Requests;
use Hash;
use Auth;


class SettingsController extends Controller
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
    //
    public function officeEntry(){
        if($officeId = Auth::user()->access=="A"){
            $offices = db::select(db::raw("select * from offices where status=:var"),['var'=>'1']);
            return view('officeEntry',['offices'=>$offices]);
        }
        else{
            return redirect('home');
        } 
    }

    public function officeEntrySave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $officeCode = $request->get('officeCode');
            $officeName = $request->get('officeName');

            if(DB::table('offices')->where('office_code', $officeCode)->exists()){
                $request->session()->flash('flash_message_error', ' Duplicate office code.');
                return redirect()->back();
            }
            else{
                $newRecord = new Offices;
                $newRecord->office_code = $officeCode;
                $newRecord->office_name = $officeName;
                $newRecord->status = "1";
                $newRecord->save();

                $request->session()->flash('flash_message_success', ' Office Saved.');
                return redirect()->back();
            }
        }
        else{
            return redirect('home');
        }
    }

    public function officeEntryDelete(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $officeCode = $request->get('officeCode');
            $officeName = $request->get('officeName');

            if(DB::table('offices')->where('office_code', $officeCode)->exists()){
                $request->session()->flash('flash_message_error', ' Duplicate office code.');
                return redirect()->back();
            }
            else{
                $newRecord = new Offices;
                $newRecord->office_code = $officeCode;
                $newRecord->office_name = $officeName;
                $newRecord->status = "1";
                $newRecord->save();

                $request->session()->flash('flash_message_success', ' Office Saved.');
                return redirect()->back();
            }
        }
        else{
            return redirect('home');
        }
    }

    public function officeDelete(Request $request, $id){
        if($officeId = Auth::user()->access=="A"){
            $record = Offices::find($id);
            $record->status = "0";
            $record->save();

            $request->session()->flash('flash_message_success', ' Office Deleted.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }

    public function officeEdit($id){
        if($officeId = Auth::user()->access=="A"){
            $offices = db::select(db::raw("select * from offices where status=:var"),['var'=>'1']);

            
            $officeDetail = db::select(db::raw("select * from offices where id=:var"),['var'=>$id]);
            foreach ($officeDetail as $key => $value){
                $oc = $value->office_code;
                $on = $value->office_name;
            }

            return view('officeEdit',['offices'=>$offices, 'oc'=>$oc, 'on'=>$on, 'id'=>$id]);
        }
        else{
            return redirect('home');
        }
    }

    public function officeEditSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $officeId = $request->get('officeId');
            $officeName = $request->get('officeName');
            $officeCode = $request->get('officeCode');
            $oldOfficeCode = $request->get('oldOfficeCode');

            if($officeCode=="REC"){
                $request->session()->flash('flash_message_error', ' Edit for REC disabled.');
                return redirect('/office_entry');
            }
            else{
                
                $record = Offices::find($officeId);
                $record->office_code = $officeCode;
                $record->office_name = $officeName;
                $record->save();

                //update document_transaction table

                db::select(db::raw("update document_transaction set office_code=:varNew where office_code=:var"),['var'=>$oldOfficeCode, 'varNew'=>$officeCode]);

                db::select(db::raw("update document_transaction set route_office_code=:varNew where route_office_code=:var"),['var'=>$oldOfficeCode, 'varNew'=>$officeCode]);

                $request->session()->flash('flash_message_success', ' Edit Success.');
                return redirect('/office_entry');
            }
        }
        else{
            return redirect('home');
        }
    }

    public function documentTypeEntry(){
        if($officeId = Auth::user()->access=="A"){
            $documentType = db::select(db::raw("select * from document_type where status=:var"),['var'=>'1']);

            return view('documentTypeEntry',['documentType'=>$documentType]);
        }
        else{
            return redirect('home');
        }
    }

    
    public function documentTypeEntrySave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $documentCode = $request->get('documentCode');
            $documentType = $request->get('documentType');

            if(DB::table('document_type')->where('document_code', $documentCode)->exists()){
                $request->session()->flash('flash_message_error', ' Duplicate document code.');
                return redirect()->back();
            }
            else{
                $newRecord = new DocumentType;
                $newRecord->document_code = $documentCode;
                $newRecord->document_type = $documentType;
                $newRecord->status = "1";
                $newRecord->save();

                $request->session()->flash('flash_message_success', ' Document Type Saved.');
                return redirect()->back();
            }
        }
        else{
            return redirect('home');
        }
    }

    public function documentTypeEdit($id){
        if($officeId = Auth::user()->access=="A"){
            $documentType = db::select(db::raw("select * from document_type where status=:var"),['var'=>'1']);
            
            $documentTypeDetail = db::select(db::raw("select * from document_type where id=:var"),['var'=>$id]);
            foreach ($documentTypeDetail as $key => $value){
                $dc = $value->document_code;
                $dt = $value->document_type;
            }

            return view('documentTypeEdit',['documentType'=>$documentType, 'dc'=>$dc, 'dt'=>$dt, 'id'=>$id]);
        }
        else{
            return redirect('home');
        }
    }

    public function documentTypeEditSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $documentTypeId = $request->get('documentTypeId');
            $documentTypeCode = $request->get('documentTypeCode');
            $documentType = $request->get('documentType');

            $record = DocumentType::find($documentTypeId);
            $record->document_code = $documentTypeCode;
            $record->document_type = $documentType;
            $record->save();

            $request->session()->flash('flash_message_success', ' Edit Success.');
            return redirect('/document_type_entry');
        }
        else{
            return redirect('home');
        }
    }

    public function documentTypeDelete(Request $request,$id){
        if($officeId = Auth::user()->access=="A"){
            $record = DocumentType::find($id);
            $record->status = "0";
            $record->save();

            $request->session()->flash('flash_message_success', ' Document Type Deleted.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }

    public function deliveryMethodEntry(){
        if($officeId = Auth::user()->access=="A"){
            $deliveryMethod = db::select(db::raw("select * from delivery_method where status=:var"),['var'=>'1']);

            return view('deliveryMethodEntry',['deliveryMethod'=>$deliveryMethod]);
        }
        else{
            return redirect('home');
        }
    }

    public function deliveryMethodEntrySave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $deliveryMethod = $request->get('deliveryMethod');

            if(DB::table('delivery_method')->where('method', $deliveryMethod)->exists()){
                $request->session()->flash('flash_message_error', ' Duplicate delivery method.');
                return redirect()->back();
            }
            else{
                $newRecord = new DeliveryMethod;
                $newRecord->method = $deliveryMethod;
                $newRecord->status = "1";
                $newRecord->save();

                $request->session()->flash('flash_message_success', ' Document Type Saved.');
                return redirect()->back();
            }
        }
        else{
            return redirect('home');
        }
    }

     public function deliveryMethodEntryEdit($id){
        if($officeId = Auth::user()->access=="A"){
            $deliveryMethod = db::select(db::raw("select * from delivery_method where id=:var"),['var'=>$id]);
            foreach ($deliveryMethod as $key => $value){
                $method = $value->method;
               
            }

            $deliveryMethod = db::select(db::raw("select * from delivery_method where status=:var"),['var'=>'1']);

            return view('deliveryMethodEntryEdit',['deliveryMethod'=>$deliveryMethod, 'method'=>$method, 'id'=>$id]);
        }
        else{
            return redirect('home');
        }
    }

    public function deliveryMethodEntryEditSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $deliveryMethod = $request->get('deliveryMethod');
            $deliveryMethodId = $request->get('deliveryMethodId');

            $record = DeliveryMethod::find($deliveryMethodId);
            $record->method = $deliveryMethod;
            $record->save();

            $request->session()->flash('flash_message_success', ' Edit Success.');
            return redirect('/delivery_method_entry');
        }
        else{
            return redirect('home');
        }
    }

    public function deliveryMethodDelete(Request $request,$id){
        if($officeId = Auth::user()->access=="A"){
            $record = DeliveryMethod::find($id);
            $record->status = "0";
            $record->save();

            $request->session()->flash('flash_message_success', ' Delivery Method Deleted.');
            return redirect('/delivery_method_entry');
        }
        else{
            return redirect('home');
        }
    }

    public function transactionTypeEntry(){
        if($officeId = Auth::user()->access=="A"){

            $transactionType = db::select(db::raw("select * from transaction_type where status=:var"),['var'=>'1']);

            return view('transactionTypeEntry',['transactionType'=>$transactionType]);
        }
        else{
            return redirect('home');
        }
    }

    public function transactionTypeEntrySave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $transactionType = $request->get('transactionType');
            $days = $request->get('days');

            if(DB::table('transaction_type')->where('transaction_type', $transactionType)->exists()){
                $request->session()->flash('flash_message_error', ' Duplicate transaction type.');
                return redirect()->back();
            }
            else{
                $newRecord = new TransactionType;
                $newRecord->transaction_type = $transactionType;
                $newRecord->days = $days;
                $newRecord->status = '1';
                $newRecord->save();

                $request->session()->flash('flash_message_success', ' Transaction Type Saved.');
                return redirect()->back();
            }
        }
        else{
            return redirect('home');
        }
    }

    public function transactionTypeEdit($id){
        if($officeId = Auth::user()->access=="A"){
            $transactionType = db::select(db::raw("select * from transaction_type where id=:var"),['var'=>$id]);
            foreach ($transactionType as $key => $value){
                $id = $value->id;
                $tt = $value->transaction_type;
                $days = $value->days;
               
            }


            $transactionType = db::select(db::raw("select * from transaction_type where status=:var"),['var'=>'1']);
            return view('transactionTypeEdit',['transactionType'=>$transactionType, 'tt'=>$tt, 'days'=>$days, 'id'=>$id]);
        }
        else{
            return redirect('home');
        }
    }

    public function transactionTypeEditSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $transactionId = $request->get('transactionId');
            $transactionType = $request->get('transactionType');
            $days = $request->get('days');

            $record = TransactionType::find($transactionId);
            $record->transaction_type = $transactionType;
            $record->days = $days;
            $record->save();

            $request->session()->flash('flash_message_success', ' Edit Success.');
            return redirect('/transaction_type_entry');
        }
        else{
            return redirect('home');
        }
    }

    public function transactionTypeDelete(Request $request,$id){
        if($officeId = Auth::user()->access=="A"){
            $record = TransactionType::find($id);
            $record->status = "0";
            $record->save();

            $request->session()->flash('flash_message_success', ' Transaction Type  Deleted.');
            return redirect('/transaction_type_entry');
        }
        else{
            return redirect('home');
        }
    }

    public function performanceStandards(){
        if($officeId = Auth::user()->access=="A"){
            $docTypes = db::select(db::raw("select * from document_type where status=:var order by id asc"),['var'=>'1']);
            $offices = db::select(db::raw("select * from offices where status=:var order by office_code asc"),['var'=>'1']);

            //
            $checkTable = db::select("select count(id)counter from office_performance");
            foreach ($checkTable as $key => $checkTableValue) {
                $checker = $checkTableValue->counter;
            }
            if($checker==0){
                //add transaction_calibration initial
                $add = db::select("insert into office_performance (id,office_id,document_code_id,office_time,created_at,updated_at) select (NULL)id,(a.id)office_id,(b.id)document_code_id,(7200)office_time,('2019-08-16 01:00:00')created_at,('2019-08-16 01:00:00')updated_at from offices a, document_type b where a.status='1' and b.status='1'");
            }
            else{
                $listOffices = db::select("select * from offices where status='1'");
                foreach ($listOffices as $key => $listOfficesVal) {
                    $officeId = $listOfficesVal->id;

                    $checker = db::select("select count(id)counter from office_performance where office_id='$officeId'");
                    foreach ($checker as $key => $checkerVal) {
                        $counterChecker = $checkerVal->counter;
                    }
                    if($counterChecker==0){
                        //add to calibration
                        $addToCal = db::select(db::raw("insert into office_performance (id,office_id,document_code_id,office_time,created_at,updated_at) select (NULL)id,(a.id)office_id,(b.id)document_code_id,(7200)office_time,('2019-08-16 01:00:00')created_at,('2019-08-16 01:00:00')updated_at from offices a, document_type b where a.status='1' and b.status='1' and a.id=:var1"),['var1'=>$officeId]);
                    }
                    else{
                        //no action as of this time
                    }
                }

            }
            //
            return view('performanceStandards',['docTypes'=>$docTypes, 'offices'=>$offices]);
        }
        else{
            return redirect('home');
        }
    }

    public function performanceStandardsUpdate(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $op = db::select("select * from office_performance");
            foreach ($op as $key => $opVal) { 
                $id = $opVal->id;
                $textBox = $request->get('performance'.$opVal->id);

                $update = db::select(db::raw("update office_performance set office_time= :var1 where id= :var2"),['var1'=>$textBox , 'var2'=>$id]);
            }


            $request->session()->flash('flash_message_success', ' Performance Standards Updated.');
            return redirect('/performance_standards');
        }
        else{
            return redirect('home');
        }
    }


    public function reactivateEndedTransaction(){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $offices = db::select("select * from offices where status='1'");
            return view('reactivateEndedTransaction',['offices'=>$offices]);
        }
        else{
            return redirect('home');
        }
    }

    public function reactivateTransaction(Request $request){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $barcode = $request->get('barcode');
            $office = $request->get('officeCode');

            $check = db::select(db::raw("select count(a.id)checker from document a, document_transaction b where a.id=b.document_id and office_code=:var2 and current_action='END' and a.barcode=:var"),['var'=>$barcode, 'var2'=>$office]);

            foreach ($check as $key => $checkVal){ 
                $checker = $checkVal->checker;
            }

            if($checker==0){
                $request->session()->flash('flash_message_error', ' Document transaction not found.');
                return redirect('/reactivate_ended_transaction');
            }
            else{ // check pass then update status
                $currentPassword = $request->get('password');
                $au = Hash::check($currentPassword, Auth::user()->password, []);
                if($au=='1'){

                    $trans = db::select(db::raw("select (a.id)aid,(b.id)bid from document a, document_transaction b where a.id=b.document_id and office_code=:var2 and current_action='END' and a.barcode=:var"),['var'=>$barcode, 'var2'=>$office]);
                    foreach ($trans as $key => $transVal){ 
                        $docId = $transVal->aid;
                        $tid = $transVal->bid;
                    }

                    $doc = Document::find($docId);
                    $doc->transaction_end_date = NULL;
                    $doc->document_status = "G";
                    $doc->save();

                    $record = DocumentTransaction::find($tid);
                    $record->current_action = "REC";
                    $record->release_person = "";
                    $record->release_date_time = NULL;
                    $record->release_action = "";
                    $record->remarks = "";
                    $record->status = "1";
                    $record->transaction_status = "O";
                    $record->save();


                    $request->session()->flash('flash_message_success', ' Document transaction active.');
                    return redirect('/reactivate_ended_transaction');
                }
                else{
                    $request->session()->flash('flash_message_error', ' Current password did not match.');
                    return redirect('reactivate_ended_transaction');
                }
            }
        }
        else{
            return redirect('home');
        }
    }

    public function correctReleaseRoute(){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $offices = db::select("select * from offices where status='1'");
            $offices2 = db::select("select * from offices where status='1'");

            return view('correctReleaseRoute',['offices'=>$offices, 'offices2'=>$offices2]);
        }
        else{
            return redirect('home');
        }
    }

    public function correctRoute(Request $request){
        if($officeId = Auth::user()->access=="A" || $officeId = Auth::user()->access=="R"){
            $barcode = $request->get('barcode');
            $currentOffice = $request->get('currentOffice');
            $newOffice = $request->get('newOffice');

            $check = db::select(db::raw("select count(a.id)checker from document a, document_transaction b where a.id=b.document_id and b.route_office_code=:var2 and b.current_action='REL' and b.status='1' and a.barcode=:var"),['var'=>$barcode, 'var2'=>$currentOffice]);

            foreach ($check as $key => $checkVal){ 
                $checker = $checkVal->checker;
            }
            if($checker==0){
                $request->session()->flash('flash_message_error', ' Document transaction not found.');
                return redirect('/correct_release_route');
            }
            else{
                $currentPassword = $request->get('password');
                $au = Hash::check($currentPassword, Auth::user()->password, []);

                $trans = db::select(db::raw("select (b.id)bid from document a, document_transaction b where a.id=b.document_id and b.route_office_code=:var2 and b.current_action='REL' and b.status='1' and a.barcode=:var"),['var'=>$barcode, 'var2'=>$currentOffice]);

                foreach ($trans as $key => $transVal){ 
                    $tid = $transVal->bid;
                }

                if($au=='1'){
                    $record = DocumentTransaction::find($tid);
                    $record->route_office_code = $newOffice;
                    $record->save();

                    $request->session()->flash('flash_message_success', ' Office route changed.');
                    return redirect('/correct_release_route');
                }
                else{
                    $request->session()->flash('flash_message_error', ' Current password did not match.');
                    return redirect('correct_release_route');
                }
            }
        }
        else{
            return redirect('home');
        }
    }

    public function createUser(){
        if($officeId = Auth::user()->access=="A"){
            $offices = db::select(db::raw("select * from offices where status=:var"),['var'=>'1']);
            $list = db::select(db::raw("select *,(a.id)aid from users a, offices b where a.office_id=b.id and a.status=:var and access!=:var2"),['var'=>'1','var2'=>'A']);


            return view('createUser',['offices'=>$offices, 'list'=>$list]);
        }
        else{
            return redirect('home');
        }
    }

    public function createUserSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $name = $request->get('name');
            $username = $request->get('username');
            $password = $request->get('password');
            $confirmPassword = $request->get('confirmPassword');
            $accessLevel = $request->get('accessLevel');
            $office = $request->get('office');
            
            if($password!=$confirmPassword){
                $request->session()->flash('flash_message_error', 'Password did not match.');
                return redirect()->back();
            }
            else{
                $newRecord = new User;
                $newRecord->name = $name;
                $newRecord->email = $username;
                $newRecord->password = bcrypt($password);

                if($accessLevel=='U'){ //users
                    $newRecord->office_id = $office;
                }
                elseif($accessLevel=='R'){
                    $newRecord->office_id = '1';
                }
                else{
                    $newRecord->office_id = '19';
                }

                $newRecord->access = $accessLevel;
                $newRecord->status = "1";
                $newRecord->save();

                $request->session()->flash('flash_message_success', ' User Saved.');
                return redirect()->back();
            }
        }
        else{
            return redirect('home');
        }
    }

    public function userDelete(Request $request,$id){
        if($officeId = Auth::user()->access=="A"){
            // $record = User::find($id);
            // $record->status = "0";
            // $record->save();
            if(Auth::user()->access=="A"){

                if(User::where('id',$id)->where('access','A')->exists()){
                    //no delete
                    return redirect('create_user');
                }
                else{
                    $res=User::where('id',$id)->delete();
                    $request->session()->flash('flash_message_success', ' User Deleted.');
                    return redirect()->back();
                }
            }
            else{
                return redirect('create_user');
            }
        }
        else{
            return redirect('home');
        }
    }

    public function changePassword(){
        return view('changePassword');
    }

    public function changePasswordSave(Request $request){
        $id = Auth::user()->id;

        if($request->get('confirmPassword')!=$request->get('newPassword')){
            $request->session()->flash('flash_message_error', 'New and Confirm Password did not match.');
            return redirect()->back();
        }
        else{
            $currentPassword = $request->get('currentPassword');
            $au = Hash::check($currentPassword, Auth::user()->password, []);

            if($au=='1'){
                $Record = User::find($id);
                $Record->password = bcrypt($request->get('newPassword'));
        
                $Record->save();
                $request->session()->flash('flash_message_success', ' Password Updated.');

                return redirect()->back();
            }
            else{
                $request->session()->flash('flash_message_error', ' Current Password did not match.');
                return redirect()->back();        
            }

        }
    }

    public function redirect(){
        return abort(404);
    }

    public function editUser(Request $request, $id){
        if($officeId = Auth::user()->access=="A"){
            $offices = db::select(db::raw("select * from offices where status=:var"),['var'=>'1']);
            $list = db::select(db::raw("select *,(a.id)aid from users a, offices b where a.office_id=b.id and a.status=:var and access!=:var2"),['var'=>'1','var2'=>'A']);

            $record = User::find($id);
            $name = $record->name;
            $username = $record->email;
            $access = $record->access;
            $officeId = $record->office_id;

            $recOffice = Offices::find($officeId);
            $officeId = $recOffice->id;
            $officeCode = $recOffice->office_code;
            $officeName = $recOffice->office_name;


            return view('editUser',['offices'=>$offices, 'list'=>$list, 'name'=>$name, 'username'=>$username, 'access'=>$access, 'officeId'=>$officeId, 'officeCode'=>$officeCode, 'officeName'=>$officeName, 'officeId'=>$officeId, 'id'=>$id  ]);
        }
        else{
            return redirect('home');
        }
    }

    public function editUserSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $name = $request->get('name');
            $username = $request->get('username');
            $accessLevel = $request->get('accessLevel');
            $office = $request->get('office');
            $userId = $request->get('userId');
            
           
            $newRecord = User::find($userId);
            $newRecord->name = $name;

            if($accessLevel=='U'){ //usersc:
                $newRecord->office_id = $office;
            }
            elseif($accessLevel=='R'){
                $newRecord->office_id = '1';
            }
            else{
                $newRecord->office_id = '19';
            }

            $newRecord->access = $accessLevel;
            $newRecord->status = "1";
            $newRecord->save();

            $request->session()->flash('flash_message_success', ' User Saved.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }

    public function resetPassword($id,$email){

        if($officeId = Auth::user()->access=="A"){
            return view('resetPassword',['email'=>$email, 'id'=>$id]);
        }
        else{
            return redirect('home');
        }

    }

    public function resetPasswordSave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            //$id = Auth::user()->id;

            if($request->get('confirmPassword')!=$request->get('newPassword')){
                $request->session()->flash('flash_message_error', 'New and Confirm Password did not match.');
                return redirect()->back();
            }
            else{

                $adminPassword = $request->get('adminPassword');
                $au = Hash::check($adminPassword, Auth::user()->password, []);

                if($au=='1'){
                    $Record = User::find($request->get('userId'));
                    $Record->password = bcrypt($request->get('newPassword'));
            
                    $Record->save();
                    $request->session()->flash('flash_message_success', ' Password Updated.');

                    return redirect()->back();
                }
                else{
                    $request->session()->flash('flash_message_error', ' Admin Password did not match.');
                    return redirect()->back();        
                }

            }
        }
        else{
            return redirect('home');
        }
    }



    public function holidays(){
        if($officeId = Auth::user()->access=="A"){
            $currentYear = date('Y');
            $holidayList = db::select(db::raw("select *,date_format(holiday_date,'%M %d, %Y')holidate from holidays where status='1' and holiday_date like '$currentYear%'"),['var'=>'1']);

            return view('holidays',['holidayList'=>$holidayList]);
        }
        else{
            return redirect('home');
        }
    }

    public function holidaySave(Request $request){
        if($officeId = Auth::user()->access=="A"){
            $newRecord = new Holidays;
            $newRecord->holiday = $request->get('holidayName');
            $newRecord->holiday_date = $request->get('holidayDate');
            $newRecord->status = "1";
            $newRecord->save();

            $request->session()->flash('flash_message_success', ' Holiday Saved.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }

    public function holidayDelete(Request $request, $id){
        if($officeId = Auth::user()->access=="A"){
            $record = Holidays::find($id);
            $record->delete();

            $request->session()->flash('flash_message_success', ' Holiday Deleted.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }

    public function correctTransactionStatus(Request $request, $id, $bid){
        if($officeId = Auth::user()->access=="A"){
            
            db::select(db::raw("update document_transaction set transaction_status='O' where document_id=:var and id=:var2 "),['var'=>$id, 'var2'=>$bid]);
            
            $request->session()->flash('flash_message_success', ' Status Corrected.');
            return redirect()->back();
        }
        else{
            return redirect('home');
        }
    }
}
