<?php

namespace App;

use Carbon;

class EmployeePayroll {
	function __construct($payroll) {
		$contract = $payroll->contract;
		$employee = $contract->employee;

		// Common data
		$this->employee_id = $employee->id;
		$this->month_id = $payroll->procedure->month->order;
		$this->payroll_id = $payroll->id;
		$this->previous_month_balance = $payroll->previous_month_balance;
		$this->next_month_balance = $payroll->next_month_balance;
		$this->nua_cua = $employee->nua_cua;
		$this->ci = $employee->identity_card;
		$this->id_ext = $employee->city_identity_card->shortened;
		$this->insurance_company_id = $contract->insurance_company_id;
		$this->ci_ext = $this->ci . ' ' . $this->id_ext;
		$this->first_name = $employee->first_name;
		$this->last_name = $employee->last_name;
		$this->mothers_last_name = $employee->mothers_last_name;
		$this->full_name = implode(" ", [$this->last_name, $this->mothers_last_name, $this->first_name]);
		$this->account_number = $employee->account_number;
		$this->birth_date = Carbon::parse($employee->birth_date)->format('d/m/Y');
		$this->gender = $employee->gender;
		$this->charge = $contract->position->charge->name;
		$this->position = $contract->position->name;
		$this->start_date = Carbon::parse($contract->start_date)->format('d/m/Y');
		$this->end_date = is_null($contract->end_date) ? 'Indefinido' : Carbon::parse($contract->end_date)->format('d/m/Y');
		$this->retirement_date = is_null($contract->retirement_date) ? null : Carbon::parse($contract->retirement_date)->format('d/m/Y');
		$this->base_wage = floatval($payroll->position->charge->base_wage);
		$this->management_entity = $employee->management_entity->name;
		$this->management_entity_id = $employee->management_entity->id;
		$this->unworked_days = $payroll->unworked_days;
		$this->worked_days = $this->worked_days($payroll);
		$this->ovt = (object) [
			'insurance_company_id' => $contract->insurance_company->ovt_id,
			'management_entity_id' => $employee->management_entity->ovt_id,
			'contract_mode' => $employee->lastContract()->contract_mode->ovt_id,
			'contract_type' => $employee->lastContract()->contract_type->ovt_id,
		];

		// Payable template
		$this->discount_old = null;
		$this->discount_common_risk = null;
		$this->discount_commission = null;
		$this->discount_solidary = null;
		$this->discount_national = null;
		$this->total_amount_discount_law = null;
		$this->net_salary = null;
		$this->discount_rc_iva = null;
		$this->discount_faults = null;
		$this->total_discounts = null;
		$this->payable_liquid = null;
		$this->employeeDiscounts($payroll);

		// Employer template
		$this->contribution_insurance_company = null;
		$this->contribution_professional_risk = null;
		$this->contribution_employer_solidary = null;
		$this->contribution_employer_housing = null;
		$this->total_contributions = null;
		$this->employerContribution($payroll);

		// Extra data
		$this->position_group = $contract->position->position_group->name;
		$this->position_group_id = $contract->position->position_group->id;
		$this->employer_number = $contract->insurance_company->employer_number->number;
		$this->employer_number_id = $contract->insurance_company->employer_number->id;
		$this->valid_contract = (is_null($contract->end_date) || $contract->status) ? true : (Carbon::parse($contract->end_date)->gte(Carbon::create($payroll->procedure->year, $payroll->procedure->month->id)->endOfMonth()) || Carbon::parse($contract->end_date)->gte(Carbon::create($payroll->procedure->year, $payroll->procedure->month->id, 30)) || $contract->status);
	}

	public function setZeroAccounts() {
		$this->base_wage = 0;
		$this->quotable = 0;
		$this->discount_old = 0;
		$this->discount_common_risk = 0;
		$this->discount_commission = 0;
		$this->discount_solidary = 0;
		$this->discount_national = 0;
		$this->total_amount_discount_law = 0;
		$this->discount_commission = 0;
		$this->net_salary = 0;
		$this->discount_rc_iva = 0;
		$this->discount_faults = 0;
		$this->total_discounts = 0;
		$this->payable_liquid = 0;
		$this->contribution_insurance_company = 0;
		$this->contribution_professional_risk = 0;
		$this->contribution_employer_solidary = 0;
		$this->contribution_employer_housing = 0;
		$this->total_contributions = 0;
	}

	private function employeeDiscounts($payroll) {
		$this->quotable = $this->base_wage * $this->worked_days / 30;
		$this->discount_old = $this->quotable * $payroll->procedure->employee_discount->elderly;
		$this->discount_common_risk = $this->quotable * $payroll->procedure->employee_discount->common_risk;
		$this->discount_commission = $this->quotable * $payroll->procedure->employee_discount->comission;
		$this->discount_solidary = $this->quotable * $payroll->procedure->employee_discount->solidary;
		$this->discount_national = $this->quotable * $payroll->procedure->employee_discount->national;
		$this->total_amount_discount_law = $this->discount_old + $this->discount_common_risk + $this->discount_commission + $this->discount_solidary + $this->discount_national;
		$this->net_salary = $this->quotable - $this->total_amount_discount_law;
		$this->discount_rc_iva = $payroll->rc_iva;
		$this->discount_faults = $payroll->faults;
		$this->total_discounts = $this->total_amount_discount_law + $this->discount_faults;
		$this->payable_liquid = round(($this->quotable - $this->total_discounts), 2);
	}

	private function employerContribution($payroll) {
		if ($payroll->contract->insurance_company->active) {
			$this->contribution_insurance_company = $this->quotable * $payroll->procedure->employer_contribution->insurance_company;
		}
		$this->contribution_professional_risk = $this->quotable * $payroll->procedure->employer_contribution->professional_risk;
		$this->contribution_employer_solidary = $this->quotable * $payroll->procedure->employer_contribution->solidary;
		$this->contribution_employer_housing = $this->quotable * $payroll->procedure->employer_contribution->housing;
		$this->total_contributions = round(($this->contribution_insurance_company + $this->contribution_professional_risk + $this->contribution_employer_solidary + $this->contribution_employer_housing), 2);
	}

	public function setWorked_days($worked_days, $payroll) {
		$this->worked_days = $worked_days;
		$this->employeeDiscounts($payroll);
		$this->employerContribution($payroll);
	}

	public function getWorked_days() {
		return $this->worked_days;
	}

	public function setValidContact($valid_contract) {
		$this->valid_contract = $valid_contract;
	}

	private function worked_days($payroll) {
		$payroll_date = (object) array(
			'year' => $payroll->procedure->year,
			'month' => $payroll->procedure->month->order,
		);

		$last_day_of_month = Carbon::create($payroll_date->year, $payroll_date->month, 1)->endOfMonth()->day;

		$date_start = Carbon::parse($payroll->contract->start_date);

		$date_end = Carbon::parse($payroll->contract->end_date);

		$worked_days = 0;

		if ($payroll->contract->end_date == null) {
			$worked_days = 30;
		} else if ($date_start->year == $date_end->year && $date_start->month == $date_end->month) {
			if ($date_end->day == $last_day_of_month && ($last_day_of_month < 30 || $last_day_of_month > 30)) {
				$worked_days = 30 - $date_start->day;
			} else {
				$worked_days = $date_end->day - $date_start->day;
			}
			$worked_days += 1;
		} else if ($date_start->year <= $payroll_date->year && $date_start->month == $payroll_date->month) {
			$worked_days = 30 + 1 - $date_start->day;
		} else if ($date_end->year >= $payroll_date->year && $date_end->month == $payroll_date->month
		) {
			$worked_days = $date_end->day;
		} else if (($date_start->year <= $payroll_date->year && $date_start->month < $payroll_date->month) ||
			($date_end->year >= $payroll_date->year && $date_end->month > $payroll_date->month)
		) {
			$worked_days = 30;
		} else if ($date_start->year < $payroll_date->year && $date_end->year > $payroll_date->year) {
			$worked_days = 30;
		} else {
			$worked_days = 0;
		}

		if ($payroll->unworked_days > $worked_days) {
			return 0;
		} else {
			return $worked_days - $payroll->unworked_days;
		}
	}
}