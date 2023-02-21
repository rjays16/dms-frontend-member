<template>
    <el-dialog class="member-cf-dialog" title="Add Collection Fee to a Member" :visible.sync="showDialog" :before-close="closeModal">
        <div class="form-group">
            <label>Active Member</label>
            <el-select :loading="is_loading_all_members"
                v-model="$v.form.member_id.$model" filterable class="w-100" placeholder="Select Member">
                <el-option v-for="option in activeMembers" :key="option.id" :value="option.id"
                    :label="option.last_name + ', ' + option.first_name" />
            </el-select>
            <div v-if="$v.form.member_id.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.member_id.required">Required Field</div>
            </div>
        </div>

        <div class="form-group">
            <label>Active Collection Fee</label>
            <el-select v-model="$v.form.collection_fee_id.$model" class="w-100" placeholder="Select Collection Fee">
                <el-option v-for="option in activeCollectionFees" :key="option.id" :value="option.id"
                    :label="option.payment_type_name + ' ' + (option.description ? '['+option.description+'] ' : '')
                        + ( ': Php. ' + option.amount ) + ' '
                        + (option.fiscal_period ? '('+fiscalMoment(option.fiscal_period.start_period) + ' - ' + fiscalMoment(option.fiscal_period.end_period)+')'
                            : (option.year ? '('+option.year+')' + ' ' : ''))"/>
            </el-select>
            <div v-if="$v.form.collection_fee_id.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.form.collection_fee_id.required">Required Field</div>
            </div>
        </div>

        <span slot="footer" class="dialog-footer">
            <el-button @click="closeModal()">Cancel</el-button>
            <el-button type="primary" @click="recheckInputs()" :disabled="disableSaveBtn">Save</el-button>
        </span>
    </el-dialog>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import moment from 'moment'
export default {
    name: 'AddCollectionFeeToMember',
    props: ["token", "apiDomain"],
    data() {
        return {
            form: {
                member_id: null,
                collection_fee_id: null,
            },

            activeMembers: [],
            activeCollectionFees: [],

            is_loading_all_members: false,
            showDialog: false,
            disableSaveBtn: false,
        }
    },
    validations: {
        form: {
            member_id: { 
                required
            },
            collection_fee_id: { 
                required
            }, 
        },
    },
    methods: {
        openModal() {
            this.is_loading_all_members = true
            this.getActiveMembers()
            this.getActiveCollectionFees()
            this.showDialog = true
        },
        closeModal() {
            this.showDialog = false
            this.is_loading_all_members = false
        },
        fiscalMoment(date) {
            return moment(date).format('MMMM, YYYY');
        },
        getActiveMembers() {
            axios.get(this.apiDomain + "/api/getallmembers", {  headers: { "Authorization": "Bearer " + this.token } })
			.then(res => {
                this.activeMembers = res.data.data
                this.is_loading_all_members = false
			})
			.catch(err => {
                // console.error(err)
                this.$message.error("Could not get list of Active members.")
			});	
        },
        getActiveCollectionFees() {
            axios.get(this.apiDomain + "/api/collection-fees/active", { headers: { "Authorization": "Bearer " + this.token } })
			.then(res => {
                this.activeCollectionFees = res.data
			})
			.catch(err => {
                // console.error(err)
                this.$message.error("Could not get list of Active collection fees.")
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
            axios.put(this.apiDomain + "/api/collection-fees/member", this.form, { headers: { Authorization: "Bearer " + this.token } })
            .then(res => {
                this.$message.success(res.data.message)
                this.showDialog = false
                this.is_loading_all_members = false
                this.disableSaveBtn = false
            })
            .catch(err => {
                this.disableSaveBtn = false

                if(err.response.status == 400) {
					this.$message.error(err.response.data.message);
				} else {
					this.$message.error("Could not add Collection Fee to Member. Please contact the site admin.")
                }
            });
        },
    }
}
</script>