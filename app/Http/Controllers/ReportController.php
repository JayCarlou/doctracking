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
use App\Offices;
use Hash;

class ReportController extends Controller
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

    public function reportCustom(){
    	$transactionType = db::table('transaction_type')
    		->where('status','1')
    		->get();

    	$documentType = db::table('document_type')
    		->where('status','1')
    		->get();

    	$sourceType = db::table('source')
    		->where('status','1')
    		->get();

    	$deliveryMethod = db::table('delivery_method')
    		->where('status','1')
    		->get();

    	return view('reportCustom',['transactionType'=>$transactionType, 'documentType'=>$documentType, 'sourceType'=>$sourceType, 'deliveryMethod'=>$deliveryMethod]);

    }

    public function reportCustomGenerate(Request $request){
    	$title = $request->get('title');
    	$dateFrom = $request->get('dateFrom')." 01:00:00";
    	$dateTo = $request->get('dateTo')." 23:59:59";

        //checkboxes
        $receiveDate = $request->get('receiveDate');
        $barcode = $request->get('barcode');
        $documentType = $request->get('documentType');
        $sourceType = $request->get('sourceType');
        $sourceName = $request->get('sourceName');
        $deliveryMethod = $request->get('deliveryMethod');
        $sex = $request->get('sex');
        $contactNo = $request->get('contactNo');
        $email = $request->get('email');
        $subjectMatter = $request->get('subjectMatter');
        $totalTime = $request->get('totalTime');
        $transactionType = $request->get('transactionType');

        //conditions
        $whereTType = $request->get('conTransactionType');
        if($whereTType==""){
            $whereTType = "";
        }
        else{
            $whereTType = 'and transaction_type_id='.$request->get('conTransactionType');
        }

        $whereDType = $request->get('conDocumentType');
        if($whereDType==""){
            $whereDType = "";
        }
        else{
            $whereDType = ' and document_type_id='.$request->get('conDocumentType');
        }

        $whereSType = $request->get('conSourceType');
        if($whereSType==""){
            $whereSType = "";
        }
        else{
            $whereSType = ' and source_type_id='.$request->get('conSourceType');
        }

        $whereDelType = $request->get('conDeliveryMethod');
        if($whereDelType==""){
            $whereDelType = "";
        }
        else{
            $whereDelType = ' and delivery_method_id='.$request->get('conDeliveryMethod');
        }

        $whereCSex = $request->get('conClientSex');
        if($whereCSex==""){
            $whereCSex = "";
        }
        else{
            $whereCSex = " and sex='".$request->get('conClientSex')."'";
        }        
       
        $whereDStatus = $request->get('documentStatus');
        if($whereDStatus==""){
            $whereDStatus = "";
        }
        else{
            $whereDStatus = " and document_status='".$request->get('documentStatus')."'";
        } 

        $whereSubjectMatter = $request->get('subjectMat');
        if($whereSubjectMatter==""){
            $whereSubjectMatter = "";
        }
        else{
            $whereSubjectMatter = " and subject_matter like '%".$request->get('subjectMat')."%'";
        } 

        
        // continue condition on the query

        $result = db::select(db::raw("select *,FLOOR(HOUR(TIMEDIFF(receive_date, transaction_end_date)) / 24)days,MOD(HOUR(TIMEDIFF(receive_date, transaction_end_date)), 24)hours, MINUTE(TIMEDIFF(receive_date, transaction_end_date))minutes,(a.id)aid  from document a, transaction_type b, document_type c, source d, delivery_method e where a.transaction_type_id=b.id and a.document_type_id=c.id and d.id=a.source_type_id and e.id=a.delivery_method_id $whereTType $whereDType $whereSType $whereDelType $whereCSex $whereDStatus $whereSubjectMatter and  receive_date between :var1 and :var2 "),['var1'=>$dateFrom, 'var2'=>$dateTo]);

        $resultCounter = db::select(db::raw("select count(a.id)aid from document a, transaction_type b, document_type c, source d, delivery_method e where a.transaction_type_id=b.id and a.document_type_id=c.id and d.id=a.source_type_id and e.id=a.delivery_method_id $whereTType $whereDType $whereSType $whereDelType $whereCSex $whereDStatus $whereSubjectMatter and  receive_date between :var1 and :var2 "),['var1'=>$dateFrom, 'var2'=>$dateTo]);
        foreach ($resultCounter as $key => $value) {
            $resultCounter = $value->aid;
        }

    	return view('reportCustomGenerate',['title'=>$title, 'dateFrom'=>$dateFrom, 'dateTo'=>$dateTo ,'receiveDate'=>$receiveDate, 'barcode'=>$barcode, 'documentType'=>$documentType, 'sourceType'=>$sourceType, 'sourceName'=>$sourceName, 'sex'=>$sex, 'contactNo'=>$contactNo, 'email'=>$email, 'subjectMatter'=>$subjectMatter, 'totalTime'=>$totalTime, 'deliveryMethod'=>$deliveryMethod, 'transactionType'=>$transactionType, 'result'=>$result, 'resultCounter'=>$resultCounter]);
    }


    public function officePerformance(){
        return view('officePerformance');
    }

    public function officePerformanceGenerate(Request $request){
        $dateFrom = $request->get('dateFrom')." 01:00:00";
        $dateTo = $request->get('dateTo')." 23:59:59";

        $offices = db::table('offices')
            ->where('status','=','1')
            ->orderByRaw('office_code ASC' )
            ->get();

        return view('officePerformanceGenerate',['dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'offices'=>$offices]);
    }

    public function summary(){

        return view('summary');
    }

    public function summaryGenerate(Request $request){
        $dateFrom = $request->get('dateFrom')." 01:00:00";
        $dateTo = $request->get('dateTo')." 23:59:59";

        $documentType = db::table('document_type')
            ->where('status','=','1')
            ->get();


        $maleClient = db::table('document')
            ->whereBetween('receive_date', array($dateFrom,$dateTo))
            ->where('status','!=','0')
            ->where('sex','=','Male')
            ->count();

         $femaleClient = db::table('document')
            ->whereBetween('receive_date', array($dateFrom,$dateTo))
            ->where('status','!=','0')
            ->where('sex','=','Female')
            ->count();



        return view('summaryGenerate',['dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'documentType'=>$documentType, 'maleClient'=>$maleClient, 'femaleClient'=>$femaleClient ]);

    }

    public function officeTransactionSummary(){

        $offices = db::table('offices')
            ->where('status','=','1')
            ->orderByRaw('office_code ASC' )
            ->get();

        return view('officeTransactionSummary', ['offices'=>$offices]);
    }

    public function officeTransactionSummaryGenerate(Request $request){

        $dateFrom = $request->get('dateFrom')." 01:00:00";
        $dateTo = $request->get('dateTo')." 23:59:59";
        $office = $request->get('office');

        $persons = db::table('users')
            ->where('office_id','=',$office)
            ->where('status','=','1')
            ->get();

        $office = Offices::find($office);
        $officeCode = $office->office_code;
        $officeName = $office->office_name;

        return view('officeTransactionSummaryGenerate',['dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'persons'=>$persons, '$officeCode'=>$officeCode, 'officeName'=>$officeName]);
    }

    public function unendedTransaction(){
        $offices = db::table('offices')
            ->where('status','=','1')
            ->orderByRaw('office_code ASC' )
            ->get(); 

        return view('unendedTransaction', ['offices'=>$offices]);
    }

    public function unendedTransactionGenerate(Request $request){
        $office = $request->get('office');

        $office = Offices::find($office);
        $officeCode = $office->office_code;
        $officeName = $office->office_name;

        // $docs = db::table('document_transaction')
        //     ->leftJoin('document','document.id','=','document_transaction.document_id')
        //     ->leftJoin('document_type','document_type.id','=','document.document_type_id')
        //     ->where('document_transaction.route_office_code','=','$officeCode')
        //     ->where('document_transaction.status','=','1')
        //     ->orWhere('document_transaction.current_action','REC')
        //     ->orWhere('document_transaction.current_action','REL')
        //     ->get();

        $docs = db::select(db::raw("select *,date_format(c.receive_date_time,'%b. %d, %Y')dte from document a, document_type b, document_transaction c where a.document_type_id=b.id and a.id=c.document_id and (c.current_action='REC' or c.current_action='REL') and c.status='1' and office_code=:var order by a.id asc"),['var'=>$officeCode]);
        

        return view('unendedTransactionGenerate',['officeName'=>$officeName, 'docs'=>$docs]);   
    }

    public function showReport($b,$ds,$tid,$cm,$lbl){

        if($b=="1"){
            $basis = " and  a.receive_date like '%".$cm."%' ";
            $counterBasis = "receive_date";
        }
        else{
            $basis = " and a.transaction_end_date like '%".$cm."%' ";
            $counterBasis = "transaction_end_date";
        }

        $queryList = db::select(db::raw("select *,date_format(a.receive_date,'%b. %d, %Y')dte,(a.id)aid from document a, document_type b, source c where a.document_type_id=b.id and c.id=a.source_type_id and a.status=:var and a.document_status=:var2 and a.transaction_type_id=:var3 $basis"),['var'=>'1','var2'=>$ds, 'var3'=>$tid]);
        
        //echo "select * from document a, document_type b, source c where a.document_type_id=b.id and c.id=a.source_type_id and a.status='1' and a.document_status='$ds' and a.transaction_type_id='$tid' $basis";

        $counter = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=',$ds)
            ->where('transaction_type_id',$tid)
            ->where($counterBasis,'like','%'.$cm.'%')
            ->count();

        return view('showReportGenerate',['queryList'=>$queryList, 'lbl'=>$lbl, 'counter'=>$counter]);
    }   

    public function forDueGenerate($oc){
        $dueTomDate = date('Y-m-d', strtotime('+1 day'));

        $counter = db::table('document')
            ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            ->where('document.end_date','like','%'.$dueTomDate.'%')
            ->where('document.document_status','=','G')
            ->where('document.status','=','1')
            ->where('document_transaction.route_office_code','=',$oc)
            ->where('document_transaction.status','=','1')
            ->count();

        $queryList = db::select(db::raw("select *,date_format(a.receive_date,'%b. %d, %Y')dte from document a, document_transaction b, document_type c where b.document_id=a.id and a.end_date like '%".$dueTomDate."%' and a.document_status='G' and a.status='1' and b.status='1' and b.route_office_code=:var and c.id=a.document_type_id" ),['var'=>$oc]);

        $offices = db::table('offices')
            ->where('office_code','=',$oc)
            ->get();
        foreach ($offices as $key => $value) {
            $officeName = $value->office_name;
        }

        return view('forDueGenerate',['queryList'=>$queryList, 'officeName'=>$officeName, 'counter'=>$counter ]);
    }


    public function delinquentsReport($cm){
        //sample query
        //$queryList = db::select("select *,count(b.office_code)no_of_del from document a, document_transaction b, offices c where a.id=b.document_id and c.office_code=b.office_code and b.transaction_status='D' group by c.office_code having count(b.office_code)>=1 order by count(b.office_code)desc");

        // $queryList = db::table('document')
        //     ->leftJoin('document_transaction','document.id','=','document_transaction.document_id')
        //     ->leftJoin('offices','document_transaction.office_code','=','offices.office_code')
        //     ->select(db::raw("*,count(document_transaction.office_code)no_of_del"))
        //     ->groupBy('offices.office_code')
        //     ->havingRaw("count (document_transaction.office_code)>=1")
        //     ->get();

        return view('delinquentsGenerate',['cm'=>$cm]);

    }

    public function delinquentsReportDetailed($cm){

        $cm = $cm;
        $cmSQL = $cm.'%';

        $queryList = db::select(db::raw("select *,(b.id)bid,(b.receive_date_time)rcvdate,(b.release_date_time)reldate,(a.id)aid,date_format(b.receive_date_time,'%b %d, %Y %h:%i:%s')date1,date_format(b.release_date_time,'%b %d, %Y %h:%i:%s')date,FLOOR(HOUR(TIMEDIFF(release_date_time, receive_date_time)) / 24)tdays,MOD(HOUR(TIMEDIFF(release_date_time, receive_date_time)), 24)thours, MINUTE(TIMEDIFF(release_date_time, receive_date_time))tminutes from document a, document_transaction b, offices c where a.id=b.document_id and c.office_code=b.office_code and b.transaction_status='D' and b.receive_date_time like :var"),['var'=>$cmSQL]);

        return view('delinquentsReportDetailed',['queryList'=>$queryList, 'cm'=>$cm]);

    }

    public function documentList($cm){
        $count = db::table('document')
            ->where('receive_date','like','%'.$cm.'%')
            ->where('status','=','1')
            ->count();

        $queryList = db::table('document')
            ->leftJoin('document_type','document.document_type_id','=','document_type.id')
            ->select(db::raw('document.id, document.receive_date, document.barcode, document.source_name, document.subject_matter, document_type.document_code'))
            ->where('document.receive_date','like','%'.$cm.'%')
            ->where('document.status','=','1')
            ->orderByRaw('document.id DESC')
            ->get();

        return view('documentListGenerate',['count'=>$count, 'queryList'=>$queryList, 'cm'=>$cm]);
    }

    public function toDoList($cm){
        $officeId = Auth::user()->office_id;
        $office = db::table('offices')
            ->where('id','=',$officeId)
            ->get();
        foreach ($office as $key => $val) {
            $officeCode = $val->office_code;
        }

        $count = db::table('document')
            ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            ->where('document.status','=','1')
            ->where('document_transaction.current_action','=','REL')
            ->where('document_transaction.route_office_code','=',$officeCode)
            ->count();

        $queryList = db::table('document')
            ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            ->leftJoin('document_type','document_type.id','=','document.document_type_id')
            ->select(db::raw('document.id, document.receive_date, document.barcode, document.source_name, document.subject_matter, document_type.document_code'))
            //->where('document.receive_date','like','%'.$cm.'%')
            ->where('document.status','=','1')
            ->where('document_transaction.current_action','=','REL')
            ->where('document_transaction.route_office_code','=',$officeCode)
            ->get();

        return view('toDoListGenerate',['count'=>$count, 'queryList'=>$queryList, 'cm'=>$cm]);
    }

    public function clientSource($type,$cm,$b){
        if($type=="1"){
            $label = "Internal Clients";
        }
        else{
            $label = "External Clients";
        }

        if($b=="1"){
            $count = db::table('document')
                ->where('receive_date','like','%'.$cm.'%')
                ->where('source_type_id','=',$type)
                ->where('status','1')
                ->count();

            $queryList = db::table('document')
                ->leftJoin('document_type','document.document_type_id','=','document_type.id')
                ->select(db::raw('document.id, document.receive_date, document.barcode, document.source_name, document.subject_matter, document_type.document_code'))
                ->where('document.receive_date','like','%'.$cm.'%')
                ->where('document.source_type_id','=',$type)
                ->where('document.status','1')
                ->get();
        }
        else{
            $count = db::table('document')
                ->where('end_date','like','%'.$cm.'%')
                ->where('source_type_id','=',$type)
                ->where('status','1')
                ->count();

            $queryList = db::table('document')
                ->leftJoin('document_type','document.document_type_id','=','document_type.id')
                ->select(db::raw('document.id, document.receive_date, document.barcode, document.source_name, document.subject_matter, document_type.document_code'))
                ->where('document.end_date','like','%'.$cm.'%')
                ->where('document.source_type_id','=',$type)
                ->where('document.status','1')
                ->get();
        }
        

        return view('clientSourceGenerate',['label'=>$label,'count'=>$count, 'queryList'=>$queryList, 'cm'=>$cm]);
    }
}
