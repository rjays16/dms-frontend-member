<template>
    <el-dialog class="collection-fees-dialog" title="Collection Fee" :close-on-click-modal="false"
        :visible.sync="showDialog" :before-close="closeModal">
        <div class="form-group">
            <label>Payment Type</label>
            <el-select v-model="$v.form.payment_type.$model" class="w-100" placeholder="Select">
                <el-option
                    v-for="option in paymentTypes"
                    :key="option.id"
                    :label="option.name"
                    :value="option.id" />
            </el-select>
            <div v-if="$v.form.payment_type.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.payment_type.required">Required Field</div>
            </div>
        </div>

        <div class="form-group">
            <label>Fiscal Period</label>
            <el-select v-model="$v.form.fiscal_period_id.$model" class="w-100" placeholder="Select">
                <el-option v-for="option in fiscalPeriods" :key="option.id" :value="option.id"
                    :label="(fiscalMoment(option.start_period) + ' - ' + fiscalMoment(option.end_period))" />
            </el-select>
            <div v-if="$v.form.fiscal_period_id.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.fiscal_period_id.required">Required Field</div>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <el-input v-model="$v.form.description.$model" type="textarea" :rows="4" />
            <div v-if="$v.form.description.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.description.required">Required Field</div>
                <div class="note color-FF2C2C" v-if="!$v.form.description.maxLength">Max of {{ $v.form.description.$params.maxLength.max }} characters.</div>
            </div>
        </div>

        <div class="form-group">
            <label>Amount</label>
            <el-input-number v-model="$v.form.amount.$model" :min="0" :precision="0" :step="1" controls-position="right" class="w-100" />
            <div v-if="$v.form.amount.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.amount.required">Required Field</div>
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <div>
                <el-radio v-model="$v.form.status.$model"
                    v-for="option in statusTypes"
                    :key="option.id"
                    :label="option.value"
                    :value="option.value">
                    {{ option.name }}
                </el-radio>
            </div>
            <div v-if="$v.form.status.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.status.required">Required Field</div>
            </div>
        </div>

        <div class="form-group">
            <label>Enable Uploading of Deposit Slip?</label>
            <div>
                <el-radio v-model="$v.form.enable_deposit_slip.$model"
                    v-for="option in yesNoOptions"
                    :key="option.id"
                    :label="option.value"
                    :value="option.value">
                    {{ option.name }}
                </el-radio>
            </div>
            <div v-if="$v.form.enable_deposit_slip.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.enable_deposit_slip.required">Required Field</div>
            </div>
        </div>

        <div class="form-group">
            <label>Enable Ideapay Fee?</label>
            <div>
                <el-radio v-model="$v.form.enable_ideapay_fee.$model"
                    v-for="option in yesNoOptions"
                    :key="option.id"
                    :label="option.value"
                    :value="option.value">
                    {{ option.name }}
                </el-radio>
            </div>
            <div v-if="$v.form.enable_ideapay_fee.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.enable_ideapay_fee.required">Required Field</div>
            </div>
        </div>

        <!-- <div class="form-group">
            <label>Valid Until:</label>
            <el-date-picker class="w-100" v-model="$v.form.valid_until.$model" type="date" value-format="yyyy-MM-dd" placeholder="Select Date" />
        </div> -->
        <span slot="footer" class="dialog-footer">
            <el-button @click="closeModal()">Cancel</el-button>
            <el-button type="primary" @click="recheckInputs()" :disabled="disableSaveBtn">Save</el-button>
        </span>
    </el-dialog>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'
import moment from 'moment'
export default {
    name: 'AddEditCollectionFee',
    props: ["token", "apiDomain"],
    data() {
        return {
            form: {
                id: null,
                description: null,
                payment_type: 1,
                year: "",
                start_period: null,
                end_period: null,
                amount: null,
                status: 1,
                enable_deposit_slip: 1,
                enable_ideapay_fee: 1,
                fiscal_period_id: null,
            },            
            
            paymentTypes: [],
            fiscalPeriods: [],

            statusTypes: [
                { id: 1, value: true, name: "Active" },
                { id: 2, value: false, name: "Inactive" },
            ],

            yesNoOptions: [
                { id: 1, value: true, name: "Yes" },
                { id: 2, value: false, name: "No" }
            ],
            
            showDialog: false,
            disableSaveBtn: false,
        }
    },
    validations: {
        form: {
            payment_type: {
                required
            },
            fiscal_period_id: { 
                required
            },
            description: {
                required,
                maxLength: maxLength(255)
            },
            amount: {
                required
            },
            status: {
                required
            },
            enable_deposit_slip: {
                required
            },
            enable_ideapay_fee: {
                required
            },
        },
    },
    methods: {
        openModal(is_editing_fee = false, item = {}) {
            this.is_editing_fee = is_editing_fee
            if(!is_editing_fee) {
                this.form = {
                    id: null,
                    description: null,
                    payment_type: 1,
                    year: null,
                    start_period: null,
                    end_period: null,
                    amount: null,
                    status: 1,
                    enable_deposit_slip: 1,
                    enable_ideapay_fee: 1,
                    fiscal_period_id: null,
                }
                this.$v.form.$reset()
            } else {
                this.form = item
            }

            this.getPaymentTypes()
            this.getFiscalPeriods()
            this.showDialog = true
        },
        closeModal() {
            this.showDialog = false
        },
        fiscalMoment(date) {
            return moment(date).format('MMMM, YYYY');
        },
        getPaymentTypes() {
            axios.get(this.apiDomain + '/api/orders/types', { headers: { "Authorization": "Bearer " + this.token } })
			.then(res => {
                this.paymentTypes = res.data
			})
			.catch(err => {
                // console.error(err)
			});	
        },
        getFiscalPeriods() {
            axios.get(this.apiDomain + '/api/fiscal-period/all', { headers: { "Authorization": "Bearer " + this.token } })
			.then(res => {
                this.fiscalPeriods = res.data
			})
			.catch(err => {
                // console.error(err)
			});	
        },        
        recheckInputs() {
            this.$v.form.$touch()
            if(this.$v.form.$invalid) {
                this.$message.error("Please fill in the required fields properly.")
            } else {
                this.disableSaveBtn = true
                this.submit()
            }
        },
        submit() {
            let api_string = this.is_editing_fee ? `/api/collection-fees/${this.form.id}` : '/api/collection-fees'

            this.$confirm('Continue?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			})
            .then(() => {
				axios.post(this.apiDomain + api_string, this.form, { headers: { Authorization: "Bearer " + this.token } })
                .then(res => {
                    this.$message.success(res.data.message)
                    this.showDialog = false
                    this.$parent.getCollectionFees()
                    this.disableSaveBtn = false
                })
                .catch(err => {                    
                    this.disableSaveBtn = false
                    this.$message.error(err.response.data.message)
                });
			})
            .catch(() => {
				this.$message({type: 'info', message: 'Cancelled'});
                this.disableSaveBtn = false
			});
        },
    }
}
</script>