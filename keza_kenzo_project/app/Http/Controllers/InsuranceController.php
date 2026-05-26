<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use App\Models\UserInsurance;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::where('is_active', true)->get();
        return response()->json([
            'success' => true,
            'insurances' => $insurances
        ]);
    }

    public function storeUserInsurance(Request $request)
    {
        $request->validate([
            'insurance_id' => 'required|exists:insurances,id',
            'policy_number' => 'required|string|unique:user_insurances,policy_number',
            'member_id' => 'required|string',
            'expiry_date' => 'required|date|after:today'
        ]);

        // Deactivate previous primary insurance if setting new primary
        if ($request->boolean('is_primary')) {
            UserInsurance::where('user_id', auth()->id())
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $userInsurance = UserInsurance::create([
            'user_id' => auth()->id(),
            'insurance_id' => $request->insurance_id,
            'policy_number' => $request->policy_number,
            'member_id' => $request->member_id,
            'expiry_date' => $request->expiry_date,
            'is_primary' => $request->boolean('is_primary', true),
            'is_active' => true,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Insurance added successfully',
            'user_insurance' => $userInsurance->load('insurance')
        ]);
    }

    public function getUserInsurances()
    {
        $userInsurances = UserInsurance::where('user_id', auth()->id())
            ->with('insurance')
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'user_insurances' => $userInsurances
        ]);
    }

    public function setPrimaryInsurance($userInsuranceId)
    {
        $userInsurance = UserInsurance::where('id', $userInsuranceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Deactivate all other primary insurances
        UserInsurance::where('user_id', auth()->id())
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        // Set new primary
        $userInsurance->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Primary insurance updated successfully'
        ]);
    }

    public function verifyInsurance(Request $request)
    {
        $request->validate([
            'insurance_id' => 'required|exists:insurances,id',
            'policy_number' => 'required|string',
            'member_id' => 'required|string'
        ]);

        $insurance = Insurance::findOrFail($request->insurance_id);
        
        // Simulate insurance verification with enhanced validation
        $verificationResult = $this->performInsuranceVerification($request, $insurance);
        
        return response()->json([
            'success' => true,
            'verified' => $verificationResult['verified'],
            'insurance' => $insurance,
            'coverage_percentage' => $insurance->coverage_percentage,
            'coverage_details' => $verificationResult['coverage_details'],
            'verification_code' => $verificationResult['verification_code'],
            'message' => $verificationResult['message']
        ]);
    }
    
    public function updateUserInsurance(Request $request, $userInsuranceId)
    {
        try {
            $userInsurance = UserInsurance::where('id', $userInsuranceId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            
            $validated = $request->validate([
                'policy_number' => 'required|string',
                'member_id' => 'required|string',
                'expiry_date' => 'required|date|after:today',
                'notes' => 'nullable|string'
            ]);
            
            $userInsurance->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Insurance updated successfully',
                'user_insurance' => $userInsurance->load('insurance')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating insurance: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroyUserInsurance($userInsuranceId)
    {
        try {
            $userInsurance = UserInsurance::where('id', $userInsuranceId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            
            $userInsurance->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Insurance removed successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing insurance: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function performInsuranceVerification($request, $insurance)
    {
        // Simulate calling insurance provider API
        $policyNumber = $request->policy_number;
        $memberId = $request->member_id;
        
        // Mock validation rules
        $isValid = true;
        $verificationCode = 'VER-' . strtoupper(uniqid());
        $message = 'Insurance verified successfully';
        $coverageDetails = [
            'policy_status' => 'active',
            'member_status' => 'active',
            'coverage_type' => 'comprehensive',
            'annual_limit' => 500000, // RWF
            'remaining_limit' => 350000, // RWF
            'covered_medicines' => 'all_prescribed_medicines',
            'exclusions' => ['cosmetic_products', 'over_the_counter_without_prescription'],
            'copayment_required' => $insurance->coverage_percentage < 100,
            'copayment_percentage' => 100 - $insurance->coverage_percentage
        ];
        
        // Simulate different scenarios based on insurance provider
        switch ($insurance->code) {
            case 'RAMA':
                // RAMA typically has higher coverage for public sector
                $coverageDetails['annual_limit'] = 800000;
                $coverageDetails['remaining_limit'] = 600000;
                $coverageDetails['special_benefits'] = ['chronic_medications', 'vaccines'];
                break;
                
            case 'MMI':
                // Private insurance with moderate coverage
                $coverageDetails['annual_limit'] = 600000;
                $coverageDetails['remaining_limit'] = 400000;
                $coverageDetails['special_benefits'] = ['specialist_medications'];
                break;
                
            case 'SANLAM':
                // Premium insurance with good coverage
                $coverageDetails['annual_limit'] = 1000000;
                $coverageDetails['remaining_limit'] = 750000;
                $coverageDetails['special_benefits'] = ['all_medications', 'medical_equipment'];
                break;
        }
        
        // Mock validation checks
        if (strlen($policyNumber) < 5) {
            $isValid = false;
            $message = 'Invalid policy number format';
            $coverageDetails['policy_status'] = 'invalid';
        } elseif (strlen($memberId) < 3) {
            $isValid = false;
            $message = 'Invalid member ID format';
            $coverageDetails['member_status'] = 'invalid';
        }
        
        return [
            'verified' => $isValid,
            'verification_code' => $verificationCode,
            'message' => $message,
            'coverage_details' => $coverageDetails
        ];
    }
}
