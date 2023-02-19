<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;

class LoanController extends Controller
{
    public function createLoan(Request $request)
    {
        $loan = new Loan;
        $loan->amount = $request->amount;
        $loan->term = $request->term;
        $loan->save();
        $loan->id;

        return response()->json([
            "message" => "loan record created",
            "loan_id" => $loan->id
        ], 201);
    }

    public function pendingLoans($loanId)
    {
        $responseArr = array();
        if(!empty($loanId))
        {
            $loan = Loan::find($loanId);
            // echo "<pre>"; print_r($loan); exit;
            $loanAmt = $loan['amount'];
            $loanTerm = $loan['term'];
            $loanDate = substr($loan['created_at'], 0, 10);
            $emiDatesArr = array();

            for($i = 1; $i <= $loanTerm; $i++)
            {
                $noOfDaysAdd = ($i * 7);
                $emiDatesArr[] = date('Y-m-d', strtotime($loanDate. ' + '.$noOfDaysAdd.' days'));
            }

            $responseArr = array(
                'loan_amount' => $loanAmt,
                'loan_term' => $loanTerm,
                'loan_date' => $loanDate,
                'loan_emi_dates' => $emiDatesArr,
            );

            return response()->json([
                "message" => "loan record found",
                "loan" => $responseArr
            ], 201);
        }
        else
        {
            return response()->json([
                "message" => "loan record not found",
            ], 201);
        }
    }

    public function loanApproveByAdmin($loanId)
    {
        $responseArr = array();
        $tempMsg = "";
        if(!empty($loanId))
        {
            $loan = Loan::find($loanId);
            if($loan->status == 0)
            {
                $loan->status = '1';
                $loan->save();
                $tempMsg = "Loan Approved By Admin, ";
            }

            $loanAmt = $loan['amount'];
            $loanTerm = $loan['term'];
            $loanDate = substr($loan['created_at'], 0, 10);
            $emiDatesArr = array();

            for($i = 1; $i <= $loanTerm; $i++)
            {
                $noOfDaysAdd = ($i * 7);
                $emiDatesArr[] = date('Y-m-d', strtotime($loanDate. ' + '.$noOfDaysAdd.' days'));
            }

            $responseArr = array(
                'loan_amount' => $loanAmt,
                'loan_term' => $loanTerm,
                'loan_date' => $loanDate,
                'loan_emi_dates' => $emiDatesArr,
            );

            return response()->json([
                "message" => $tempMsg . "loan record found",
                "loan" => $responseArr
            ], 201);
        }
        else
        {
            return response()->json([
                "message" => "loan record not found",
            ], 201);
        }
    }
}
