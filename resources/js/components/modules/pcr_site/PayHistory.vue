<template>
	<div class="pay-history-dialog">
        <el-dialog
        :title="'Payment History Activity for ' + memberName"
        :visible.sync="showDialog"
        width="80%"
		:before-close="handleClose">
            <el-table
            :data="payData"
            stripe
            style="width: 100%">
                <el-table-column
                    prop="order_id"
                    label="Transaction ID"> 
                </el-table-column>
                <el-table-column
                    prop="amount"
                    label="Name"
                    width="250">
                    <template slot-scope="scope">
                        <div class="table-user-name">{{ scope.row.pcr_member.last_name }}, {{ scope.row.pcr_member.first_name }}</div>
                    </template>                            
                </el-table-column>
                <el-table-column
                    prop="amount"
                    label="Amount"> 
                </el-table-column>                              
                <el-table-column
                    prop="order_type_name"
                    label="Order Type"> 
                    <template slot-scope="scope">
                        <div class="table-user-name">{{ scope.row.order.order_type_name }}</div>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="payment_method"
                    label="Payment Method">
                    <template slot-scope="scope">
                        <div v-if="scope.row.payment_method == 1">Ideapay</div>
                        <div v-if="scope.row.payment_method == 2">Bank</div>
                        <div v-if="scope.row.payment_method == 3">Check</div>
                    </template>                            
                </el-table-column>  
                <el-table-column
                    align="center"
                    label="Status"> 
                    <template slot-scope="scope">
                        <div class="table-user-name">{{ scope.row.order.order_status_name }}</div>
                    </template>
                </el-table-column> 
                <el-table-column
                    prop="date_paid"
                    align="center"
                    label="Date Paid">
                    <template slot-scope="scope">
                        <div>{{ scope.row.date_paid | moment }}</div>
                    </template>
                </el-table-column>

            </el-table>			
		</el-dialog>
	</div>
</template>

<script>
import moment from "moment";

export default {
    props: ["showDialog", "memberName", "tokenValue", "userID","payData"],
    data() {
        return {
			payhistory: this.payData
        }
    }, 
    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD, YYYY');
        }
    },     
    methods: {
        handleClose() {
            this.$emit('closePayHistDialog', false)
        },
		getPaymentHistory() {
			axios.get(this.apiDomain + `/api/payments/${userID}`, {
					headers: {
						Authorization: "Bearer " + this.$cookies.get("token"),
					},
				})
				.then(res => {
					this.tableLoading = false
					this.payhistory = res.data
				})
				.catch(err => {
					// console.log(err)
					this.tableLoading = false
				});	 
		}
	}
}
</script>

<style>

</style>