<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\TransactionType;
use App\DocumentType;
use App\DeliveryMethod;
use App\Source;
use DB;

class ArchiveController extends Controller
{
    //
	public function archive(){
        $searchBy = "";
		$keyword = "";
		$qry =  db::select(db::raw("select * from document a, document_type b where a.barcode like :var and a.document_type_id=b.id"),['var'=>$keyword]);
        return view('archive',['qry'=>$qry, 'searchBy'=>$searchBy, 'keyword'=>$keyword]);
    }

    public function archiveSearch(Request $request){
    	$searchBy = $request->get('searchBy');
    	$keyword = '%'.$request->get('Keyword').'%';

    	if($searchBy=="1"){ //subject matter
    		$qry =  db::select(db::raw("select *,(a.id)aid,date_format(receive_date,'%b. %d, %Y')rdate from document a, document_type b where a.subject_matter like :var and a.document_type_id=b.id and a.status='1'"),['var'=>$keyword]);
    	}
    	else if($searchBy=="2"){ //source name
    		$qry =  db::select(db::raw("select *,(a.id)aid,date_format(receive_date,'%b. %d, %Y')rdate from document a, document_type b where a.source_name like :var and a.document_type_id=b.id and a.status='1'"),['var'=>$keyword]);
    	}
    	else{ //barcode
    		$qry =  db::select(db::raw("select *,(a.id)aid,date_format(receive_date,'%b. %d, %Y')rdate from document a, document_type b where a.barcode like :var and a.document_type_id=b.id and a.status='1'"),['var'=>$keyword]);
    	}
    	$keyword = $request->get('Keyword');
    	return view('archive',['qry'=>$qry, 'searchBy'=>$searchBy, 'keyword'=>$keyword]);
    }

    public function viewTracking($id){
        $record = Document::find($id);
        $barcode = $record->barcode;
        $accessCode = $record->access_code;

        $startDate = strtotime($record->receive_date);
        $startDate = strtoupper(date("M. d, Y h:i a",$startDate));
        $endDate = strtotime($record->end_date);
        $endDate = strtoupper(date("M. d, Y h:i a",$endDate));


        $ttId = $record->transaction_type_id;
        $recordTT = TransactionType::find($ttId);
        $transactionType = $recordTT->transaction_type;

        $dtId = $record->document_type_id;
        $recordDT = DocumentType::find($dtId);
        $documentType = $recordDT->document_type;

        $stId = $record->source_type_id;
        $recordS = Source::find($stId);
        $source = $recordS->source;

        $oId = $record->office_id;

        $dmId = $record->delivery_method_id;
        $recordDM = DeliveryMethod::find($dmId);
        $deliveryMethod = $recordDM->method;

        $sourceName = $record->source_name;
        $subjectMatter = $record->subject_matter;
        $documentsLinked = $record->documents_linked;

        $attachments = db::select(db::raw("select * from document_attachments where parent_id=:var"),['var'=>$id]);

        //$docTransactions = db::select(db::raw("select *,date_format(receive_date_time,'%b %d, %Y')date,date_format(receive_date_time,'%l:%i %p')time,date_format(release_date_time,'%b %d, %Y')reldate,date_format(release_date_time,'%l:%i %p')reltime, FLOOR(HOUR(TIMEDIFF(release_date_time, receive_date_time)) / 24)odays,MOD(HOUR(TIMEDIFF(release_date_time, receive_date_time)), 24)ohours, MINUTE(TIMEDIFF(release_date_time, receive_date_time))ominutes, FLOOR(HOUR(TIMEDIFF(transit_date_time, release_date_time)) / 24)tdays,MOD(HOUR(TIMEDIFF(transit_date_time, release_date_time)), 24)thours, MINUTE(TIMEDIFF(transit_date_time, release_date_time))tminutes  from document_transaction where document_id=:var order by sequence desc"),['var'=>$id]);

        // $docTransactions = db::select(db::raw("select *,date_format(receive_date_time,'%b %d, %Y')date,date_format(receive_date_time,'%l:%i %p')time,date_format(release_date_time,'%b %d, %Y')reldate,date_format(release_date_time,'%l:%i %p')reltime, FLOOR(HOUR(TIMEDIFF(release_date_time, receive_date_time)) / 24)odays,MOD(HOUR(TIMEDIFF(release_date_time, receive_date_time)), 24)ohours, MINUTE(TIMEDIFF(release_date_time, receive_date_time))ominutes, FLOOR(HOUR(TIMEDIFF(transit_date_time, release_date_time)) / 24)tdays,MOD(HOUR(TIMEDIFF(transit_date_time, release_date_time)), 24)thours, MINUTE(TIMEDIFF(transit_date_time, release_date_time))tminutes,TIME_TO_SEC(TIMEDIFF(release_date_time, receive_date_time))osec, TIME_TO_SEC(TIMEDIFF(transit_date_time, release_date_time))tsec  from document_transaction where document_id=:var order by sequence desc"),['var'=>$id]);

        $docTransactions = db::select(db::raw("select *,date_format(receive_date_time,'%b %d, %Y')date,date_format(receive_date_time,'%l:%i %p')time,date_format(release_date_time,'%b %d, %Y')reldate,date_format(release_date_time,'%l:%i %p')reltime, TIMESTAMPDIFF(day,receive_date_time, release_date_time)odays, MOD(HOUR(TIMEDIFF(release_date_time, receive_date_time)), 24)ohours, MINUTE(TIMEDIFF(release_date_time, receive_date_time))ominutes, FLOOR(HOUR(TIMEDIFF(transit_date_time, release_date_time)) / 24)tdays,MOD(HOUR(TIMEDIFF(transit_date_time, release_date_time)), 24)thours, MINUTE(TIMEDIFF(transit_date_time, release_date_time))tminutes,TIME_TO_SEC(TIMEDIFF(release_date_time, receive_date_time))osec, TIME_TO_SEC(TIMEDIFF(transit_date_time, release_date_time))tsec  from document_transaction where document_id=:var order by sequence desc"),['var'=>$id]);
        
    	return view('viewTracking',['barcode'=>$barcode, 'accessCode'=>$accessCode, 'startDate'=>$startDate, 'endDate'=>$endDate, 'transactionType'=>$transactionType, 'documentType'=>$documentType, 'deliveryMethod'=>$deliveryMethod, 'source'=>$source, 'sourceName'=>$sourceName, 'subjectMatter'=>$subjectMatter, 'documentsLinked'=>$documentsLinked, 'attachments'=>$attachments, 'docTransactions'=>$docTransactions]);
    }
}
