<?php
  
namespace App\Exports;
use Illuminate\Support\Facades\DB;
use App\Models\ElectionVote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Query\Builder;
  
class PCRElectionResultExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
        protected $id;

    function __construct($id) {
        $this->id = $id;
    }

    public function collection()
    {
        $select = DB::select('select v.voted_by as "Voter_id",CONCAT(voter.first_name," ",voter.middle_name," ",voter.last_name)as "Voter",n.member_id as "Nominee_id", CONCAT(nominee.first_name," ",nominee.middle_name," ",nominee.last_name)as "Nominee" from election_votes v LEFT JOIN ( select first_name,last_name,middle_name,id from pcr_members)as voter on v.voted_by = voter.id LEFT JOIN election_nominees n on n.id = v.election_nominee_id LEFT JOIN ( select first_name,last_name,middle_name,id from pcr_members)as nominee on nominee.id = n.member_id where n.election_period_id = '.$this->id.' ORDER BY n.member_id ASC');
        return collect( $select );
    }

    public function headings(): array
    {
        return [
            'Nominee ID',
            'Nominee',
            'Voter ID',
            'Voter'
        ];
    }
    public function map($vote): array
    {
        return [
            $vote->Nominee_id,
            $vote->Nominee,
            $vote->Voter_id,
            $vote->Voter
        ];
    }

}