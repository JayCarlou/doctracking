<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class HomeController extends Controller
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
    public function index()
    {   
        //for the form
        $month = date('m');
        $year = date('Y');
        $basis = "";

        $months = db::table('months')->get();

        $chosenMonth = db::table('months')
            ->where('month_value','=',$month) 
            ->get();

        foreach ($chosenMonth as $key => $value) {
            $chosenMonth = $value->month_value;
            $chosenMonthName = $value->month;
        }

        $userOfficeId = Auth::user()->office_id;

        $office = db::table('offices')
            ->where('id','=',$userOfficeId)
            ->get();

        foreach ($office as $key => $value) {
            $currentOffice = $value->office_code;
        }

        // $currentDate = date('Y-m-d');
        $currentMonth = date('Y-m');
        $dueTomDate = date('Y-m-d', strtotime('+1 day'));
        $currentDateTime = date('Y-m-d h:i:s');

        //ontime
        $ontimeSimple = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','O')
            ->where('transaction_type_id','=','1')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $ontimeComplex = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','O')
            ->where('transaction_type_id','=','2')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $ontimeHigh = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','O')
            ->where('transaction_type_id','=','3')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        //ongoing 
        $ongoingSimple = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','G')
            ->where('transaction_type_id','=','1')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $ongoingComplex = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','G')
            ->where('transaction_type_id','=','2')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $ongoingHigh = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','G')
            ->where('transaction_type_id','=','3')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        //delayed
        $delayedSimple = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','D')
            ->where('transaction_type_id','=','1')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $delayedComplex = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','D')
            ->where('transaction_type_id','=','2')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $delayedHigh = db::table('document')
            ->where('status','=','1')
            ->where('document_status','=','D')
            ->where('transaction_type_id','=','3')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->count();

        $forDue = db::table('document')
            ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            ->where('document.end_date','like','%'.$dueTomDate.'%')
            ->where('document.document_status','=','G')
            ->where('document.status','=','1')
            ->where('document_transaction.status','=','1')
            ->where('document_transaction.route_office_code','=',$currentOffice)
            ->count();

        //no of docs
        $noOfDocs = db::table('document')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->where('status','=','1')
            ->count();

        //clients external
        $externalClients = db::table('document')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->where('source_type_id','=','2')
            ->where('status','=','1')
            ->count();

        //clients internal
        $internalClients = db::table('document')
            ->where('receive_date','like','%'.$currentMonth.'%')
            ->where('source_type_id','=','1')
            ->where('status','=','1')
            ->count();

        //delinquents
        $delinquentTransactions  = db::table('document_transaction')
            ->where('transaction_status','=','D')
            ->where('receive_date_time','like','%'.$currentMonth.'%')
            ->count();

        //to-do list
        $toDoList = db::table('document')
            ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            ->where('document.status','=','1')
            ->where('document_transaction.current_action','=','REL')
            ->where('document_transaction.route_office_code','=',$currentOffice)
            ->count();

        //average time simple  
        $avgSimplePO = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'2', 'var2'=>'1']);
        foreach ($avgSimplePO as $key => $value) {
            if($value->average==""){
                $avgSimplePO = '0';
            }
            else{
                $avgSimplePO = $value->average;
            }
        }

        $avgSimpleVC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'5', 'var2'=>'1']);
        foreach ($avgSimpleVC as $key => $value) { 
            if($value->average==""){
                $avgSimpleVC = '0';
            }
            else{
                $avgSimpleVC = $value->average;
            }
        }

        $avgSimplePY = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'4', 'var2'=>'1']);
        foreach ($avgSimplePY as $key => $value) {
            if($value->average==""){
                $avgSimplePY = '0';
            }
            else{
                $avgSimplePY = $value->average;
            }
        }

        $avgSimpleCOM = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'1', 'var2'=>'1']);
        foreach ($avgSimpleCOM as $key => $value) { 
            if($value->average==""){
                $avgSimpleCOM = '0';
            }
            else{
                $avgSimpleCOM = $value->average;
            }
        }

        $avgSimplePR = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'3', 'var2'=>'1']);
        foreach ($avgSimplePR as $key => $value) {
            if($value->average==""){
                $avgSimplePR = '0';
            }
            else{
                $avgSimplePR = $value->average;
            }
        }

        $avgSimpleAOC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'6', 'var2'=>'1']);
        foreach ($avgSimpleAOC as $key => $value) {
            if($value->average==""){
                $avgSimpleAOC = '0';
            }
            else{
                $avgSimpleAOC = $value->average;
            }
        }

        //average time complex
        $avgComplexPO = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'2', 'var2'=>'2']);
        foreach ($avgComplexPO as $key => $value) {
            if($value->average==""){
                $avgComplexPO = '0';
            }
            else{
                $avgComplexPO = $value->average;
            }
        }

        $avgComplexVC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'5', 'var2'=>'2']);
        foreach ($avgComplexVC as $key => $value) {
            if($value->average==""){
                $avgComplexVC = '0';
            }
            else{
                $avgComplexVC = $value->average;
            }
        }

        $avgComplexPY = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'4', 'var2'=>'2']);
        foreach ($avgComplexPY as $key => $value) {
            if($value->average==""){
                $avgComplexPY = '0';
            }
            else{
                $avgComplexPY = $value->average;
            }
        }

        $avgComplexCOM = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'1', 'var2'=>'2']);
        foreach ($avgComplexCOM as $key => $value) {
            if($value->average==""){
                $avgComplexCOM = '0';
            }
            else{
                $avgComplexCOM = $value->average;
            }
        }

        $avgComplexPR = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'3', 'var2'=>'2']);
        foreach ($avgComplexPR as $key => $value) {
            if($value->average==""){
                $avgComplexPR = '0';
            }
            else{
                $avgComplexPR = $value->average;
            }
        }

        $avgComplexAOC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'6', 'var2'=>'2']);
        foreach ($avgComplexAOC as $key => $value) {
            if($value->average==""){
                $avgComplexAOC = '0';
            }
            else{
                $avgComplexAOC = $value->average;
            }
        }

        //average time highly technical
        $avgHighlyPO = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'2', 'var2'=>'3']);
        foreach ($avgHighlyPO as $key => $value) {
            if($value->average==""){
                $avgHighlyPO = '0';
            }
            else{
                $avgHighlyPO = $value->average;
            }
        }

        $avgHighlyVC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'5', 'var2'=>'3']);
        foreach ($avgHighlyVC as $key => $value) {
            if($value->average==""){
                $avgHighlyVC = '0';
            }
            else{
                $avgHighlyVC = $value->average;
            }
        }

        $avgHighlyPY = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'4', 'var2'=>'3']);
        foreach ($avgHighlyPY as $key => $value) {
            if($value->average==""){
                $avgHighlyPY = '0';
            }
            else{
                $avgHighlyPY = $value->average;
            }
        }

        $avgHighlyCOM = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'1', 'var2'=>'3']);
        foreach ($avgHighlyCOM as $key => $value) {
            if($value->average==""){
                $avgHighlyCOM = '0';
            }
            else{
                $avgHighlyCOM = $value->average;
            }
        }

        $avgHighlyPR = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'3', 'var2'=>'3']);
        foreach ($avgHighlyPR as $key => $value) {
            if($value->average==""){
                $avgHighlyPR = '0';
            }
            else{
                $avgHighlyPR = $value->average;
            }
        }

        $avgHighlyAOC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'6', 'var2'=>'3']);
        foreach ($avgHighlyAOC as $key => $value) {
            if($value->average==""){
                $avgHighlyAOC = '0';
            }
            else{
                $avgHighlyAOC = $value->average;
            }
        }

        //code for alert start here
        $userAccess = Auth::user()->access;
        if($userAccess=="A" || $userAccess=="R"){ //administrator and records
            $qryTransactions = db::table('document_transaction')
                ->leftJoin('document','document.id','=','document_transaction.document_id')
                ->leftJoin('offices','offices.office_code','=','document_transaction.office_code')
                ->selectRaw("offices.id, document.document_type_id, timestampdiff(minute,document_transaction.receive_date_time,'".$currentDateTime."')inMinute, document.barcode, (document.id)did")
                ->where('document_transaction.status','=','1')
                ->where('document.status','=','1')
                ->where('document.document_status','=','G')
                ->limit(50)
                ->get();
        }
        else{ //user
            $qryTransactions = db::table('document_transaction')
                ->leftJoin('document','document.id','=','document_transaction.document_id')
                ->leftJoin('offices','offices.office_code','=','document_transaction.office_code')
                ->selectRaw("offices.id, document.document_type_id, timestampdiff(minute,document_transaction.receive_date_time,'".$currentDateTime."')inMinute, document.barcode, (document.id)did")
                ->where('offices.id','=',$userOfficeId)
                ->where('document_transaction.status','=','1')
                ->where('document.status','=','1')
                ->where('document.document_status','=','G')
                ->limit(50)
                ->get();
        }

        // foreach ($qryTransactions as $key => $val) {
        //     $resultOfficeId = $val->id;
        //     $resultDocumentType = $val->document_type_id;

        //     $qryOfficePerformance = db::table('office_performance')
        //         ->where('office_id','=',$resultOfficeId)
        //         ->where('document_code_id','=',$resultDocumentType)
        //         ->get();

        //     foreach ($qryOfficePerformance as $key => $val) {
                
        //     }
        // }


        //code for alert end here

        
        return view('home',['ontimeSimple'=>$ontimeSimple, 'ontimeComplex'=>$ontimeComplex, 'ontimeHigh'=>$ontimeHigh, 'ongoingSimple'=>$ongoingSimple, 'ongoingComplex'=>$ongoingComplex, 'ongoingHigh'=>$ongoingHigh, 'delayedSimple'=>$delayedSimple, 'delayedComplex'=>$delayedComplex, 'delayedHigh'=>$delayedHigh, 'forDue'=>$forDue, 'noOfDocs'=>$noOfDocs, 'externalClients'=>$externalClients, 'internalClients'=>$internalClients, 'delinquentTransactions'=>$delinquentTransactions, 'toDoList'=>$toDoList, 'avgSimplePO'=>$avgSimplePO, 'avgSimpleVC'=>$avgSimpleVC, 'avgSimpleCOM'=>$avgSimpleCOM, 'avgSimplePR'=>$avgSimplePR, 'avgSimpleAOC'=>$avgSimpleAOC, 'avgComplexPO'=>$avgComplexPO, 'avgComplexVC'=>$avgComplexVC, 'avgComplexPY'=>$avgComplexPY, 'avgComplexCOM'=>$avgComplexCOM, 'avgComplexPR'=>$avgComplexPR, 'avgComplexAOC'=>$avgComplexAOC, 'avgHighlyPO'=>$avgHighlyPO, 'avgHighlyVC'=>$avgHighlyVC, 'avgHighlyPY'=>$avgHighlyPY, 'avgHighlyCOM'=>$avgHighlyCOM, 'avgHighlyPR'=>$avgHighlyPR, 'avgHighlyAOC'=>$avgHighlyAOC, 'avgSimplePY'=>$avgSimplePY, 'months'=>$months, 'month'=>$month, 'year'=>$year, 'chosenMonth'=>$chosenMonth, 'chosenMonthName'=>$chosenMonthName, 'currentMonth'=>$currentMonth, 'currentOffice'=>$currentOffice, 'qryTransactions'=>$qryTransactions]);
    }

    public function generateDashboard(Request $request)
    {   
        $month = $request->get('month');
        $year = $request->get('year');
        $basis = $request->get('basis');

        $months = db::table('months')
            ->where('month_value','!=',$month)
            ->get();

        $chosenMonth = db::table('months')
            ->where('month_value','=',$month) 
            ->get();

        foreach ($chosenMonth as $key => $value) {
            $chosenMonth = $value->month_value;
            $chosenMonthName = $value->month;
        }


        $userOfficeId = Auth::user()->office_id;


        $office = db::table('offices')
            ->where('id','=',$userOfficeId)
            ->get();

        foreach ($office as $key => $value) {
            $currentOffice = $value->office_code;
        }

        $currentDate = date('Y-m-d');
        $currentMonth = $year."-".$month;
        $dueTomDate = date('Y-m-d', strtotime('+1 day'));

        // echo $basis;
        if($basis=="1"){
            //ontime
            $ontimeSimple = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','O')
                ->where('transaction_type_id','=','1')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $ontimeComplex = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','O')
                ->where('transaction_type_id','=','2')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $ontimeHigh = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','O')
                ->where('transaction_type_id','=','3')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            //ongoing 
            $ongoingSimple = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','G')
                ->where('transaction_type_id','=','1')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $ongoingComplex = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','G')
                ->where('transaction_type_id','=','2')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $ongoingHigh = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','G')
                ->where('transaction_type_id','=','3')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            //delayed
            $delayedSimple = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','D')
                ->where('transaction_type_id','=','1')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $delayedComplex = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','D')
                ->where('transaction_type_id','=','2')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $delayedHigh = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','D')
                ->where('transaction_type_id','=','3')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            // $forDue = db::table('document')
            //     ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            //     ->where('document.end_date','like','%'.$dueTomDate.'%')
            //     ->where('document.document_status','=','G')
            //     ->where('document.status','=','1')
            //     ->where('document_transaction.status','=','1')
            //     ->where('document_transaction.route_office_code','=',$currentOffice)
            //     ->count();

            //no of docs
            $noOfDocs = db::table('document')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->where('status','=','1')
                ->count();

            //clients external
            $externalClients = db::table('document')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->where('source_type_id','=','2')
                ->where('document.status','1')
                ->count();

            //clients internal
            $internalClients = db::table('document')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->where('source_type_id','=','1')
                ->where('document.status','1')
                ->count();

            //delinquents
            $delinquentTransactions  = db::table('document_transaction')
                ->where('transaction_status','=','D')
                ->where('receive_date_time','like','%'.$currentMonth.'%')
                ->count();

            //to-do list
            // $toDoList = db::table('document')
            //     ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            //     ->where('document.status','=','1')
            //     ->where('document_transaction.current_action','=','REL')
            //     ->where('document_transaction.route_office_code','=',$currentOffice)
            //     ->count();
        }
        else{
            $ontimeSimple = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','O')
                ->where('transaction_type_id','=','1')
                ->where('transaction_end_date','like','%'.$currentMonth.'%')
                ->count();

            $ontimeComplex = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','O')
                ->where('transaction_type_id','=','2')
                ->where('transaction_end_date','like','%'.$currentMonth.'%')
                ->count();

            $ontimeHigh = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','O')
                ->where('transaction_type_id','=','3')
                ->where('transaction_end_date','like','%'.$currentMonth.'%')
                ->count();

            //ongoing 
            $ongoingSimple = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','G')
                ->where('transaction_type_id','=','1')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $ongoingComplex = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','G')
                ->where('transaction_type_id','=','2')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            $ongoingHigh = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','G')
                ->where('transaction_type_id','=','3')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->count();

            //delayed
            $delayedSimple = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','D')
                ->where('transaction_type_id','=','1')
                ->where('transaction_end_date','like','%'.$currentMonth.'%')
                ->count();

            $delayedComplex = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','D')
                ->where('transaction_type_id','=','2')
                ->where('transaction_end_date','like','%'.$currentMonth.'%')
                ->count();

            $delayedHigh = db::table('document')
                ->where('status','=','1')
                ->where('document_status','=','D')
                ->where('transaction_type_id','=','3')
                ->where('transaction_end_date','like','%'.$currentMonth.'%')
                ->count();

        

            // $forDue = db::table('document')
            //     ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            //     ->where('document.end_date','like','%'.$dueTomDate.'%')
            //     ->where('document.document_status','=','G')
            //     ->where('document.status','=','1')
            //     ->where('document_transaction.route_office_code','=',$currentOffice)
            //     ->count();

            //no of docs
            $noOfDocs = db::table('document')
                ->where('receive_date','like','%'.$currentMonth.'%')
                ->where('status','=','1')
                ->count();

            //clients external
            $externalClients = db::table('document')
                ->where('end_date','like','%'.$currentMonth.'%')
                ->where('source_type_id','=','2')
                ->where('document.status','1')
                ->count();

            //clients internal
            $internalClients = db::table('document')
                ->where('end_date','like','%'.$currentMonth.'%')
                ->where('source_type_id','=','1')
                ->where('document.status','1')
                ->count();

            //delinquents
            $delinquentTransactions  = db::table('document_transaction')
                ->where('transaction_status','=','D')
                ->where('release_date_time','like','%'.$currentMonth.'%')
                ->count();

            //to-do list
            // $toDoList = db::table('document')
            //     ->leftJoin('document_transaction','document_transaction.document_id','=','document.id')
            //     ->where('document.status','=','1')
            //     ->where('document_transaction.current_action','=','REL')
            //     ->where('document_transaction.route_office_code','=',$currentOffice)
            //     ->count();


        }

        //average time simple  
        $avgSimplePO = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'2', 'var2'=>'1']);
        foreach ($avgSimplePO as $key => $value) {
            if($value->average==""){
                $avgSimplePO = '0';
            }
            else{
                $avgSimplePO = $value->average;
            }
        }

        $avgSimpleVC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'5', 'var2'=>'1']);
        foreach ($avgSimpleVC as $key => $value) { 
            if($value->average==""){
                $avgSimpleVC = '0';
            }
            else{
                $avgSimpleVC = $value->average;
            }
        }

        $avgSimplePY = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'4', 'var2'=>'1']);
        foreach ($avgSimplePY as $key => $value) {
            if($value->average==""){
                $avgSimplePY = '0';
            }
            else{
                $avgSimplePY = $value->average;
            }
        }

        $avgSimpleCOM = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'1', 'var2'=>'1']);
        foreach ($avgSimpleCOM as $key => $value) { 
            if($value->average==""){
                $avgSimpleCOM = '0';
            }
            else{
                $avgSimpleCOM = $value->average;
            }
        }

        $avgSimplePR = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'3', 'var2'=>'1']);
        foreach ($avgSimplePR as $key => $value) {
            if($value->average==""){
                $avgSimplePR = '0';
            }
            else{
                $avgSimplePR = $value->average;
            }
        }

        $avgSimpleAOC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'6', 'var2'=>'1']);
        foreach ($avgSimpleAOC as $key => $value) {
            if($value->average==""){
                $avgSimpleAOC = '0';
            }
            else{
                $avgSimpleAOC = $value->average;
            }
        }

        //average time complex
        $avgComplexPO = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'2', 'var2'=>'2']);
        foreach ($avgComplexPO as $key => $value) {
            if($value->average==""){
                $avgComplexPO = '0';
            }
            else{
                $avgComplexPO = $value->average;
            }
        }

        $avgComplexVC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'5', 'var2'=>'2']);
        foreach ($avgComplexVC as $key => $value) {
            if($value->average==""){
                $avgComplexVC = '0';
            }
            else{
                $avgComplexVC = $value->average;
            }
        }

        $avgComplexPY = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'4', 'var2'=>'2']);
        foreach ($avgComplexPY as $key => $value) {
            if($value->average==""){
                $avgComplexPY = '0';
            }
            else{
                $avgComplexPY = $value->average;
            }
        }

        $avgComplexCOM = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'1', 'var2'=>'2']);
        foreach ($avgComplexCOM as $key => $value) {
            if($value->average==""){
                $avgComplexCOM = '0';
            }
            else{
                $avgComplexCOM = $value->average;
            }
        }

        $avgComplexPR = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'3', 'var2'=>'2']);
        foreach ($avgComplexPR as $key => $value) {
            if($value->average==""){
                $avgComplexPR = '0';
            }
            else{
                $avgComplexPR = $value->average;
            }
        }

        $avgComplexAOC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'6', 'var2'=>'2']);
        foreach ($avgComplexAOC as $key => $value) {
            if($value->average==""){
                $avgComplexAOC = '0';
            }
            else{
                $avgComplexAOC = $value->average;
            }
        }

        //average time highly technical
        $avgHighlyPO = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'2', 'var2'=>'3']);
        foreach ($avgHighlyPO as $key => $value) {
            if($value->average==""){
                $avgHighlyPO = '0';
            }
            else{
                $avgHighlyPO = $value->average;
            }
        }

        $avgHighlyVC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'5', 'var2'=>'3']);
        foreach ($avgHighlyVC as $key => $value) {
            if($value->average==""){
                $avgHighlyVC = '0';
            }
            else{
                $avgHighlyVC = $value->average;
            }
        }

        $avgHighlyPY = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'4', 'var2'=>'3']);
        foreach ($avgHighlyPY as $key => $value) {
            if($value->average==""){
                $avgHighlyPY = '0';
            }
            else{
                $avgHighlyPY = $value->average;
            }
        }

        $avgHighlyCOM = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'1', 'var2'=>'3']);
        foreach ($avgHighlyCOM as $key => $value) {
            if($value->average==""){
                $avgHighlyCOM = '0';
            }
            else{
                $avgHighlyCOM = $value->average;
            }
        }

        $avgHighlyPR = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'3', 'var2'=>'3']);
        foreach ($avgHighlyPR as $key => $value) {
            if($value->average==""){
                $avgHighlyPR = '0';
            }
            else{
                $avgHighlyPR = $value->average;
            }
        }

        $avgHighlyAOC = db::select(db::raw("select avg((TIMESTAMPDIFF(minute,receive_date,transaction_end_date)*0.000694444))average from document where status='1' and document_type_id=:var and transaction_type_id=:var2 and receive_date like '%$currentMonth%'"),['var'=>'6', 'var2'=>'3']);
        foreach ($avgHighlyAOC as $key => $value) {
            if($value->average==""){
                $avgHighlyAOC = '0';
            }
            else{
                $avgHighlyAOC = $value->average;
            }
        }
        
        return view('generateDashboard',['ontimeSimple'=>$ontimeSimple, 'ontimeComplex'=>$ontimeComplex, 'ontimeHigh'=>$ontimeHigh, 'ongoingSimple'=>$ongoingSimple, 'ongoingComplex'=>$ongoingComplex, 'ongoingHigh'=>$ongoingHigh, 'delayedSimple'=>$delayedSimple, 'delayedComplex'=>$delayedComplex, 'delayedHigh'=>$delayedHigh, 'noOfDocs'=>$noOfDocs, 'externalClients'=>$externalClients, 'internalClients'=>$internalClients, 'delinquentTransactions'=>$delinquentTransactions, 'avgSimplePO'=>$avgSimplePO, 'avgSimpleVC'=>$avgSimpleVC, 'avgSimpleCOM'=>$avgSimpleCOM, 'avgSimplePR'=>$avgSimplePR, 'avgSimpleAOC'=>$avgSimpleAOC, 'avgComplexPO'=>$avgComplexPO, 'avgComplexVC'=>$avgComplexVC, 'avgComplexPY'=>$avgComplexPY, 'avgComplexCOM'=>$avgComplexCOM, 'avgComplexPR'=>$avgComplexPR, 'avgComplexAOC'=>$avgComplexAOC, 'avgHighlyPO'=>$avgHighlyPO, 'avgHighlyVC'=>$avgHighlyVC, 'avgHighlyPY'=>$avgHighlyPY, 'avgHighlyCOM'=>$avgHighlyCOM, 'avgHighlyPR'=>$avgHighlyPR, 'avgHighlyAOC'=>$avgHighlyAOC, 'avgSimplePY'=>$avgSimplePY, 'months'=>$months, 'month'=>$month, 'year'=>$year, 'chosenMonth'=>$chosenMonth, 'chosenMonthName'=>$chosenMonthName, 'basis'=>$basis, 'currentMonth'=>$currentMonth, 'currentOffice'=>$currentOffice, 'basis'=>$basis]);

    }

}
