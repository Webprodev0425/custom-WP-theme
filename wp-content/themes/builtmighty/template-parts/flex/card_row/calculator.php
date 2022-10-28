<?php
$calculators = get_field('calculators', 'option');
if(!empty($calculators)):?>
    <div class="aircraft_calculator">
        <div class="conditions">
            <div class="aircraft">
                <label>Aircraft Type:</label>
                <select name="aircraft" class="aircraft_list">
                    <option value="0">Select an Aircraft</option>
                    <?php foreach ($calculators as $calculator) :?>
                    <?php
                        $aircraft_name = $calculator['aircraft_type_name'];
                        $insurance_number = $calculator['insurance_number'];
                        $insurance = $calculator['insurance'];
                        $hangar = $calculator['hangar'];
                        $mgmt_fee = $calculator['management_fee'];
                        $aircraft_cleaning = $calculator['aircraft_cleaning'];
                        $cap_sal = $calculator['cap_sal'];
                        $cop_sal = $calculator['cop_sal'];
                        $subs_est = $calculator['subs_est'];
                        $train_est = $calculator['train_est'];
                        $var_mt_labor = $calculator['var_mt_labor'];
                        $var_mt_parts = $calculator['var_mt_parts'];
                        $var_mt_eng = $calculator['var_mt_eng'];
                        $var_apu = $calculator['var_apu'];
                        $var_fuel = $calculator['var_fuel'];
                        $hr_rate_charter = $calculator['hr_rate_charter'];
                    ?>
                        <option value="<?php echo $aircraft_name; ?>" insurance_number="<?php echo $insurance_number; ?>" insurance="<?php echo $insurance; ?>" hangar="<?php echo $hangar; ?>" mgmt_fee="<?php echo $mgmt_fee; ?>"
                                cleaning="<?php echo $aircraft_cleaning; ?>" cap_sal="<?php echo $cap_sal; ?>" cop_sal="<?php echo $cop_sal; ?>" subs_est="<?php echo $subs_est; ?>" train_est="<?php echo $train_est; ?>"
                                var_mt_labor="<?php echo $var_mt_labor; ?>" var_mt_parts="<?php echo $var_mt_parts; ?>" var_mt_eng="<?php echo $var_mt_eng; ?>" var_apu="<?php echo $var_apu; ?>" var_fuel="<?php echo $var_fuel; ?>"
                                hr_rate_charter="<?php echo $hr_rate_charter; ?>"><?php echo $aircraft_name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="owner_hours_options">
                <label for="owner_hours">Owner Hours:</label>
                <input id="owner_hours" class="owner_hours_list" type="text" placeholder="Owner Hours">
            </div>
            <div class="charter_hours_options">
                <label for="charter_hours">Charter Hours:</label>
                <input id="charter_hours" class="charter_hours_list" type="text" placeholder="Charter Hours">
            </div>
            <div class="calculate">
                <input name="calculate" class="calculate_cost" type="button" value="Calculate">
            </div>
        </div>
        <div class="results_table">
            <h1 class="aircraft_name"></h1>
            <table>
                <tr>
                    <th colspan="2">Fixed Expenses</th>
                    <th>Month</th>
                    <th>Annual</th>
                </tr>
                <tr>
                    <td>Insurance (Liability/Hull Value)</td>
                    <td class="insurance_number" style="font-weight: bold;"></td>
                    <td class="month_insurance">-</td>
                    <td class="annual_insurance">-</td>
                </tr>
                <tr class="hangar">
                    <td colspan="2">Hangar</td>
                    <td class="month_hangar">-</td>
                    <td class="annual_hangar">-</td>
                </tr>
                <tr>
                    <td colspan="2">Management Fee</td>
                    <td class="month_mgmt_fee">-</td>
                    <td class="annual_mgmt_fee">-</td>
                </tr>
                <tr>
                    <td colspan="2">Aircraft Cleaning</td>
                    <td class="month_cleaning">-</td>
                    <td class="annual_cleaning">-</td>
                </tr>
                <tr>
                    <td colspan="2">Captain's Salary (Includes Payroll Tax, Workers Comp)</td>
                    <td class="month_cap_sal">-</td>
                    <td class="annual_cap_sal">-</td>
                </tr>
                <tr>
                    <td colspan="2">Copilot's Salary (Includes Payroll Tax, Workers Comp)</td>
                    <td class="month_cop_sal">-</td>
                    <td class="annual_cop_sal">-</td>
                </tr>
                <tr>
                    <td colspan="2">Charts and Subscriptions Estimate (Does Not Include WiFi)
                    </td>
                    <td class="month_subs_est">-</td>
                    <td class="annual_subs_est">-</td>
                </tr>
                <tr>
                    <td colspan="2">Annual Crew Training Estimate</td>
                    <td class="month_train_est">-</td>
                    <td class="annual_train_est">-</td>
                </tr>
                <tr class="total_fixed">
                    <td class="row_head" colspan="2">TOTAL FIXED EXPENSES</td>
                    <td class="month_total_fixed">-</td>
                    <td class="annual_total_fixed">-</td>
                </tr>
                <tr>
                    <th colspan="4">Variable Expenses </th>
                </tr>
                <tr>
                    <td>Maintenance - Labor</td>
                    <td class="var_mt_labor" style="font-weight: bold;"></td>
                    <td class="month_maintain_labor">-</td>
                    <td class="annual_maintain_labor">-</td>
                </tr>
                <tr>
                    <td>Maintenance - Parts</td>
                    <td class="var_mt_parts" style="font-weight: bold;"></td>
                    <td class="month_maintain_parts">-</td>
                    <td class="annual_maintain_parts">-</td>
                </tr>
                <tr>
                    <td>Maintenance - Engine Program</td>
                    <td class="var_mt_eng" style="font-weight: bold;"></td>
                    <td class="month_maintain_eng_pro">-</td>
                    <td class="annual_maintain_eng_pro">-</td>
                </tr>
                <tr class="apu">
                    <td>Maintenance - APU Program</td>
                    <td class="var_apu" style="font-weight: bold;"></td>
                    <td class="month_maintain_apu">-</td>
                    <td class="annual_maintain_apu">-</td>
                </tr>
                <tr>
                    <td>Fuel (Gal Per Hour Burn)</td>
                    <td class="var_fuel" style="font-weight: bold;"></td>
                    <td class="month_fuel">-</td>
                    <td class="annual_fuel">-</td>
                </tr>
                <tr class="total_variable">
                    <td class="row_head" colspan="2">TOTAL VARIABLE EXPENSES</td>
                    <td class="month_total_variable">-</td>
                    <td class="annual_total_variable">-</td>
                </tr>
                <tr>
                    <td class="space" colspan="4"></td>
                </tr>
                <tr class="total_variable_fixed">
                    <td class="row_head" colspan="2">TOTAL FIXED & VARIABLE</td>
                    <td class="month_total_fixed_variable">-</td>
                    <td class="annual_total_fixed_variable">-</td>
                </tr>
                <tr>
                    <th colspan="4">Charter Offset</th>
                </tr>
                <tr>
                    <td colspan="2">Total Charter Revenue</td>
                    <td class="month_total_charter">-</td>
                    <td class="annual_total_charter">-</td>
                </tr>
                <tr>
                    <td colspan="2">Total Fixed & Variable Expenses</td>
                    <td class="month_total_fixed_variable">-</td>
                    <td class="annual_total_fixed_variable">-</td>
                </tr>
                <tr>
                    <td class="row_head" colspan="2">TOTAL</td>
                    <td class="month_total">-</td>
                    <td class="annual_total">-</td>
                </tr>
                <tr>
                    <td class="space" colspan="4"></td>
                </tr>
                <tr>
                    <td class="factors" colspan="4">Factors</td>
                </tr>
                <tr>
                    <td class="row_head" colspan="3">Owner Hours</td>
                    <td class="owner_hours">-</td>
                </tr>
                <tr>
                    <td class="row_head" colspan="3">Charter Hours</td>
                    <td class="charter_hours">-</td>
                </tr>
                <tr>
                    <td class="row_head" colspan="3">Total Hours</td>
                    <td class="total_hours">-</td>
                </tr>
                <tr>
                    <td class="row_head" colspan="3">Fuel Price/Gallon National AVG</td>
                    <td class="avg_fuel_price">-</td>
                </tr>
                <tr>
                    <td class="space" colspan="4"></td>
                </tr>
                <tr>
                    <td class="row_head" colspan="3">Cost per Flight Hour for Owner</td>
                    <td class="cost_per_flight_owner">-</td>
                </tr>
            </table>
        </div>
    </div>
<?php endif;