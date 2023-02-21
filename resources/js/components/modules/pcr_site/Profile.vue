<template>
	<transition name="el-zoom-in-top">
		<div class="content-wrapper page-site-profile" v-loading.fullscreen.lock="fullscreenLoading">
			<section class="page-header">
				<div class="page-site-profile-header">
					<div class="page-site-profile-header-text">Profile</div>
				</div>
			</section>
			<section class="content">
				<div class="container-xl">
					<div class="meeting-cont d-none">
						<h6><b>Dear PCR Radiologist member:</b></h6>
						<p class="mt-4">You are invited to a Zoom meeting. </p>
						<p><b>When:</b> Feb 25, 2021 06:00 PM Singapore</p>
						<p><b>Topic:</b> PCR Business Meeting 2021/General Assembly</p>
						<p class="mt-5">Note:</p>
						<p><b>PLEASE CHANGE YOUR ZOOM ACCOUNT USING YOUR FULL NAME AND IF POSSIBLE PUT DPBR OR FPCR TO FACILITATE IDENTIFICATION BY HOSTS.</b></p>
						<p class="mt-5">Register in advance for this meeting:</p>
						<el-button @click="goToMeeting()" icon="el-icon-right" type="primary">Please Click Here</el-button>
						<p class="mt-5">After registering, you will receive a confirmation email containing information about joining the meeting.</p>
						<p></p>
						<p></p>
					</div>
					<div class="row">
						<div class="col-lg-4">
							<el-card class="box-card profile-card my-3">
								<el-upload
									class="sec-profile-photo"
									action="https://jsonplaceholder.typicode.com/posts/"
									:http-request="addAttachment4"
									:auto-upload="false"
									:show-file-list="false"
									:on-success="handleDepositSuccess2"
									:before-upload="beforeAvatarUpload2"
									:on-change="handleImageUpload4"
									accept="image/png, image/jpeg, image/jpg"
									:file-list="attachments4">
									<img v-if="profile_pic" :src="profile_pic" @error="replaceByDefault" />
									<i v-else class="el-icon-camera-solid avatar-uploader-icon"
										style="background: #dddddd; font-size: 50px; padding: 20px;"></i>
								</el-upload>
								<div class="sec-profile-register mt-4 text-center">
									<el-button class="reg-goto-btn"
										:style="
											profile_pic_change == true
												? 'background-color:#409EFF !important;'
												: 'background-color:#BEBEBE !important;'
										"
										type="primary" round @click="uploadProfilePic()" :disabled="!profile_pic_change">
										Save
									</el-button>
								</div>
								<hr />
								<div class="sec-personal-info">
									<p class="mb-1 font-weight-bold">First Name</p>
									<el-input class="mb-3 name-uppercase" v-model="first_name" />

									<p class="mb-1 font-weight-bold">Middle Name</p>
									<el-input class="mb-3 name-uppercase" v-model="middle_name" />

									<p class="mb-1 font-weight-bold">Last Name</p>
									<el-input class="mb-3 name-uppercase" v-model="last_name" />

									<p class="mb-1 font-weight-bold">Date of Birth</p>
									<el-date-picker class="mb-3 w-100" v-model="date_of_birth" type="date"
										value-format="yyyy-MM-dd" placeholder="Date of Birth (Year-Month-Day)" />
								</div>
								<el-collapse accordion class="mt-3">
                                    <el-collapse-item>
                                        <template slot="title">
											<div class="accordion-title">
												<p class="font-weight-bold">PRC Information</p>
												<i class="el-icon-info" aria-hidden="true"></i>
											</div> 
										</template>
                                        <p class="mb-1 font-weight-bold">PRC Number</p>
                                        <el-input class="mb-3" v-model="prc_number" />

                                        <p class="mb-1 font-weight-bold">PRC ID Image</p>                                        
                                        <div class="text-center mb-3 con-pcr-image">
                                            <el-upload
                                                action="/"
                                                accept="image/png, image/jpeg, image/jpg"
                                                :auto-upload="false"
                                                :show-file-list="false"
                                                :on-change="onChangePRCImage">
                                                <img :src="prc_upload" v-if="prc_upload" class="py-3 mw-50" />
                                                <i v-else class="el-icon-camera-solid avatar-uploader-icon icon-pcr-id"></i>
                                            </el-upload>
                                        </div>
                                        <p class="mb-1 font-weight-bold">PRC ID Expiration Date</p>
                                        <el-date-picker class="mb-3 w-100" v-model="prc_expiration" format="MM/dd/yyyy" value-format="yyyy-MM-dd"
                                            placeholder="Date of Expiration" :picker-options="availableDateOptions" />

                                        <div class="sec-profile-register mt-2 text-center">
                                            <el-button @click="updatePRCDetails()" type="primary" round size="small">
                                                Update
                                            </el-button>
                                        </div>
                                    </el-collapse-item>
									<el-collapse-item>
										<template slot="title">
											<div class="accordion-title">
												<p class="font-weight-bold">Personal Information</p>
												<i class="el-icon-info" aria-hidden="true"></i>
											</div> 
										</template>

										<div class="sec-personal-info">
											<p class="mb-1 font-weight-bold">Email</p>
											<el-input class="mb-3" :disabled="true" v-model="email" />

											<p class="mb-1 font-weight-bold">Contact Number</p>
											<el-input class="mb-3" :disabled="true" v-model="phone_num" />

											<p class="mb-1 font-weight-bold">DMS Registration Date</p>
											<el-input class="mb-3" :disabled="true" v-model="regDate" />											

											<p class="mb-1 font-weight-bold">PMA Number</p>
											<el-input class="mb-3" :disabled="true" v-model="pma_num" />
										</div>
										<div class="mt-3">
											<p class="mb-1 font-weight-bold">Main Hospital Affiliation</p>
											<el-input class="mb-3" :disabled="true" v-model="place_of_practice" />
										</div>
										<div>
											<div class="sec-mailing-info mt-3">
												<p class="mb-3 font-weight-bold">
													Address <span class="required-item">*</span>
												</p>
												<div v-if="$v.address.country.$dirty">
													<div class="invalid-field" v-if="!$v.address.country.required">
														Required
													</div>
												</div>
												<country-select
													v-model="$v.address.country.$model"
													:country="$v.address.country.$model"
													className="country-select el-input__inner mb-3"
													:countryName="true"
												/>
												<div v-if="$v.address.region.$dirty">
													<div class="invalid-field" v-if="!$v.address.region.required">
														Required
													</div>
												</div>
												<region-select
													v-model="$v.address.region.$model"
													:country="$v.address.country.$model"
													className="region-select el-input__inner mb-3"
													:countryName="true"
													:regionName="true"
													:region="$v.address.region.$model"
												/>
												<div v-if="$v.address.city.$dirty">
													<div class="invalid-field" v-if="!$v.address.city.required">
														Required
													</div>
												</div>
												<el-input class="mb-3" placeholder="City" v-model="$v.address.city.$model" />
												<div v-if="$v.address.zip.$dirty">
													<div class="invalid-field" v-if="!$v.address.zip.required">
														Required
													</div>
												</div>
												<el-input class="mb-3" placeholder="Zip Code" v-model="$v.address.zip.$model" />
											</div>
											<div v-if="trainee_status == 1" class="sec-membership-info mt-4">
												<p class="mb-3 font-weight-bold">Membership</p>
												<span><i>Not Applicable</i></span>
											</div>
											<div v-if="trainee_status == 0" class="sec-membership-info mt-2">
												<p class="mb-3 font-weight-bold">Membership</p>
												<el-select v-model="mem_type_id" class="w-100" placeholder="Select">
													<el-option
														v-for="option in memberTypes"
														:key="option.id"
														:label="option.type_name"
														:value="option.id" />
												</el-select> 
											</div>
											<div class="mt-3">
												<p class="mb-3 font-weight-bold">Specialty Division Membership</p>
												<el-select v-model="spec_div_mem_type" class="w-100" placeholder="Select">
													<el-option label="None" :value="null" />
													<el-option
														v-for="option in specDivTypes"
														:key="option.id"
														:label="option.name"
														:value="option.id" />
												</el-select>
											</div>
											<div class="mt-3">
												<p class="mb-3 font-weight-bold">Affiliate Society Membership</p>
												<el-select v-model="affi_soc_mem_type" class="w-100" placeholder="Select">
													<el-option label="None" :value="null" />
													<el-option
														v-for="option in affiSocTypes"
														:key="option.id"
														:label="option.name"
														:value="option.id" />
												</el-select>
											</div>
										</div>
									</el-collapse-item>
									<el-collapse-item>
										<template slot="title">
											<div class="accordion-title">
												<p class="font-weight-bold">Educational Background</p>
												<i class="fa fa-graduation-cap" aria-hidden="true"></i>
											</div> 
										</template>

										<div>
											<div class="mb-3">
												<div class="mb-2">
													<p class="mb-1">College of Medicine</p>
													<el-input placeholder="College of Medicine" v-model="edu.medicine"></el-input>
												</div>
												<div class="mb-2">
													<p class="mb-1">Graduation</p>
													<el-date-picker class="w-100" v-model="edu.med_graduation" type="year" placeholder="Graduation" />
												</div>
											</div>
											<div class="mb-3">
												<div class="mb-2">
													<p class="mb-1">Specialty</p>
													<el-input placeholder="Specialty" v-model="edu.specialty"></el-input>
												</div>
											</div>
											<div class="mb-3">
												<div class="mb-2">
													<p class="mb-1">Specialty Society</p>
													<el-input placeholder="Specialty Society" v-model="edu.specialty_society"></el-input>
												</div>
											</div>
											<div class="mb-3">
												<div class="mb-2">
													<p class="mb-1">Subspecialty</p>
													<el-input placeholder="Subspecialty" v-model="edu.subspecialty"></el-input>
												</div>
											</div>
											<div class="mb-3">
												<div class="mb-2">
													<p class="mb-1">Subspecialty Society</p>
													<el-input placeholder="Subspecialty Society" v-model="edu.subspecialty_society"></el-input>
												</div>
											</div>
											<div class="mb-3">
												<div class="mb-2">
													<p class="mb-1">Master's Education</p>
													<el-input placeholder="Master's Education" v-model="edu.master_education"></el-input>
												</div>
												<div class="mb-2">
													<p class="mb-1">School</p>
													<el-input placeholder="School" v-model="edu.master_education_school"></el-input>
												</div>
											</div>
										</div>
									</el-collapse-item>
								</el-collapse>

								<div class="sec-profile-register mt-4 text-center">
									<el-button
										@click="updateMembersProfile()"
										class="reg-goto-btn"
										:style="'background-color:#409EFF !important;'"
										type="primary"
										round>
										Update
									</el-button>
								</div>
							</el-card>
						</div>
						<div class="col-lg-8">
							<div class="row">
								<div class="col">
									<el-card class="box-card profile-card w-100 mt-3">
										<div class="class-text-center mt-3">
											<div class="text-center" style="font-size: 25px">
												Your Annual Dues
											</div>
											<hr />
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<PayProcess
														@refreshHistory="getCollectionFees()"
														:showDialog="payNowDialog"
														:memberName="cert_name"
                                                        :last_name="last_name"
														:tokenValue="token"
														:userID="depUpload_id"
														:annualOrders="top_annual_orders"
                                                        :apiDomain="apiDomain"
                                                        :has_requested_cogs="has_requested_cogs"
                                                        ref="paymentList">
													</PayProcess>
												</div>
												<div class="col-sm-12 text-center">
													<el-button class="mt-3 mb-2 mx-0" size="mini" type="primary" @click="viewPaymentHistory()" round><span class="px-2">Show Payment Ledger</span></el-button>
												</div>												
											</div>
										</div>
									</el-card>
								</div>
							</div>

							<div class="col-sm-12 d-flex text-center pt-3 justify-content-center on-mob con-how-to">
								<el-button
									class="w-50 my-2 mx-2 px-3 text-uppercase"
									size="mini"
									type="primary"
									round @click="viewIdeapayVideo()"
									icon="el-icon-video-play">
									How to pay via IdeaPay
								</el-button>
								<el-button
									class="w-50 my-2 mx-2 px-3 text-uppercase"
									size="mini"
									type="primary"
									round @click="depositSlip = true"
									icon="el-icon-video-play">
									How to upload deposit slip
								</el-button>
							</div>
							
							<div class="row">
								<div class="col">
									<el-card class="box-card profile-card w-100 mt-2">
										<div class="class-text-center mt-3">
											<div class="text-center" style="font-size: 25px">
												Previous Dues
											</div>
											<hr />
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<PayProcess
														@refreshHistory="getCollectionFees()"
														:showDialog="payNowDialog"
														:memberName="cert_name"
                                                        :last_name="last_name"
														:pcr_num="prc_number"
														:memberShip="membership"
														:memberTypes="memberTypes"
														:tokenValue="token"
														:userID="depUpload_id"
														:annualOrders="previous_orders"
                                                        :has_requested_cogs="has_requested_cogs"
                                                        ref="paymentList">
													</PayProcess>
												</div>
											</div>
										</div>
									</el-card>
								</div>
							</div>
                            <!-- <div class="row">
								<div class="col-md-6">
									<el-card class="box-card profile-card mt-3">
										<div class="class-text-center">
											<div style="font-size:15px; !important" class="mt-2 text-center">
												<b>Take part in the 2022 MDMS Local Elections</b>
											</div>
											<div class="sec-profile-register mt-4 text-center">
												<div v-if="activeperiod === 1">
													<el-button
														v-if="checkVoted === 0"
														:disabled="!can_vote"
														class="elec-goto-btn"
														style="background-color: ##409eff !important;"
														type="primary"
														@click="can_vote ? gotoElection() : ''"
														round>
														PROCEED
													</el-button>
													<el-button
														v-else
														disabled
														class="reg-goto-btn"
														style="background-color: #bebebe !important;"
														type="primary"
														round>
														VOTE SUBMITTED
													</el-button>
												</div>
												<div v-else style="font-size:10px; !important" class="mt-4 mb-5 text-center">
													There's no active election in progress.
												</div>
											</div>
											<div class="mt-4 text-center" v-if="!can_vote">
												<small>
													Sorry you are not eligible to vote yet. Kindly upload the deposit slip or pay first for your 2021 Membership Fee so you can proceed.
												</small>
											</div>
										</div>
									</el-card>
								</div> 
								<div class="col-md-4">
									<el-card class="box-card profile-card mt-3">
										<div class="sec-photo text-center mt-2 mb-1">

											<el-upload
												class="avatar-uploader ava-icon-square"
												action="https://jsonplaceholder.typicode.com/posts/"
												:http-request="addAttachment3"
												:auto-upload="false"
												:show-file-list="false"
												:on-success="handleDepositSuccess"
												:before-upload="beforeAvatarUpload"
												:on-change="handleImageUpload3"
												accept="image/png, image/jpeg, image/jpg"
												:file-list="attachments3"
											>
												<img
													v-if="imageUrl3"
													:src="imageUrl3"
													class="avatar"
												/>
												<i
													v-else
													class="el-icon-camera-solid avatar-uploader-icon"
												></i>
											</el-upload>
											<div
												class="mt-3 font-italic"
												style="font-size:10px; !important"
											>
												Upload your Annual dues deposit slip here.
											</div>
											<div
												class="sec-profile-register mt-4 text-center"
											>
												<el-button
													class="reg-goto-btn"
													:style="
														image3_change == true
															? 'background-color:#409EFF !important;'
															: 'background-color:#BEBEBE !important;'
													"
													type="primary"
													round
													@click="uploadDepositSlip()"
													:disabled="!image3_change"
													>Save</el-button
												>
											</div>
										</div>
									</el-card>
								</div>
								<div class="col-md-6">
									<el-card class="box-card profile-card mt-3">
										<div
											style="font-size:15px; !important"
											class="mt-2 text-center">
											<b>Certificate of Good Standing</b><br>

											<el-button :disabled="can_generate_cert !== true" class="mt-4" type="primary" round @click="certDialog = true">GENERATE</el-button>
											

											<div class="mt-4" style="font-size:10px; !important">
												Note: Certificate can only be generated if a member is currently in good standing.
											</div>

										</div>
									</el-card>
								</div>
								<div class="col-md-6" v-if="affi_soc_mem_type">
									<el-card class="box-card profile-card mt-3 text-center">
										<h5>Affiliate Society: {{ affi_soc_mem_name }}</h5>
										<a class="el-button mt-2 el-button--primary is-round" type="primary" round :href="'/affiliate-society/?id='+affi_soc_mem_type">
											View Members
										</a>
									</el-card>
								</div>
								<div class="col-md-12">
									<el-card class="box-card profile-card mt-3">
										<h4>Your current sessions:</h4>
										<div class="sess-cont mt-0" v-for="session in assigned_sess" :key="session.id">
											
											<div class="row">
												<div class="col-sm-12 col-md-9 mini-sess-btn-body py-3">
													<h3>{{ session.topic }}</h3>
													<el-button class="mini-sess-room-btn mb-2" size="mini" type="warning" round>Event: {{ session.event_name }}</el-button>

													<div v-html="session.description"></div>
													<div class="mini-sess-date mt-4">
														<span>{{ session.start_time | moment }}</span>
													</div>
												</div>
												<div class="col-sm-12 col-md-3 mini-sess-btn-col">

													<el-button type="primary" class="join-sess-btn" round @click="joinMiniSessVid(session.id, session.join_url)">Join</el-button>
												</div>
											</div>
										</div>

									</el-card>
								</div>
                            </div> -->
								
                            <PayHistory
                                @closePayHistDialog="payHistDialog = false"
                                :showDialog="payHistDialog"
                                :memberName="cert_name"
                                :payData="paymentHistory"
                                >
                            </PayHistory>

                            <IdeapayVideo
                                @closeIdeapayDialog="ideapayVideo = false"
                                :showDialog="ideapayVideo"
                                >
                            </IdeapayVideo>

                            <DepositSlipVideo
                                @closeDialog="depositSlip = false"
                                :showDialog="depositSlip"
                                >
                            </DepositSlipVideo>
						</div> 
					</div>
				</div>
			</section>
		</div>
	</transition>
</template>

<script>
import { required, integer, email, sameAs } from "vuelidate/lib/validators/";
import moment from 'moment'

export default {
name: "Profile",
    data() {
        return {
			interval: null,
            subdomain: "",
            apiDomain: this.$store.state.apiUrl,
            fullscreenLoading: true,
            token: this.$cookies.get("token"),
            manual_token:"2073|AFMu3gfLPa0TM7QJ7rtfXKFrUMyoIUFp8bEqCkwi",

            memberTypes: [],
            specDivTypes: [],
            affiSocTypes: [],
            mem_type_id: [],
            mem_type_id_string: [],
            spec_div_mem_type: null,
            affi_soc_mem_type: null,
            affi_soc_mem_name: null,

            chapterTypes: [],
            chapter_id: 1,
            trainee_status: 1,

            goodstanding: true,
            member_id: null,
            place_of_practice: null,
            full_name: null,
            first_name: null,
            middle_name: null,
            last_name: null,
            date_of_birth: null,
            cert_name: null,
            email: null,
            phone_num: null,
            pma_num: null,
            prc_number: null,
            prc_expiration: null,
            prc_upload: null,
            prc_image: null,
            mailing_address: null,
            is_trainee: null,
            membership: null,
            prc_chapter: null,
            profile_pic: null,
            profile_pic_change: false,
            profile_pic_final: {},
            can_vote: false,
            balance: 0,
            has_pending_orders: null,
            can_generate_cert: null,
            has_requested_cogs: false,
            
            attachments3: [],
            attachments4: [],
            imageUrl3: "",
            image_types: ["image/png", "image/jpg", "image/jpeg"],
            file_max: 10000000,
            image3: {},
            image4: {},
            imageUrl3final: {},
            image3_change: false,
            training_ins: "",

            address: {
                country: "",
                region: "",
                city: "",
                zip: "",
            },

            edu: {
                medicine: "",
                med_graduation: "",
                residency: "",
                res_graduation: "",
                specialty: "",
                spec_graduation: "",
                specialty_society: "",
                specialty_society_graduation: "",
                subspecialty: "",
                subspecialty_society: "",
                subspecialty_society_induction: "",
                master_education: "",
                master_education_school: "",
            },

            activeElectionPeriod: [],
            voted: [],
            userable_type: null, 
            user_id: null,
            depUpload_id: null,
            conv_url: null,
            good_standing: null,
            allSess: [],
            mySess: [],
            assignedSess: [],
            certDialog: false,
            payHistDialog: false,
            payNowDialog: false,
            regDate: "",
            paymentHistory: [],

            annualOrders: [],
            otherOrders: [],
            
            top_annual_orders: [],
            previous_orders: [],

            depositSlip: false,
            ideapayVideo: false,

            availableDateOptions: {
                disabledDate (date) {
                    var yesterday = new Date();
                    yesterday.setDate(yesterday.getDate()-1);
                    return date < yesterday
                }
            },
        };
    },
    validations: {
        address: {
            country: { required },
            region: { required },
            city: { required },
            zip: { required },
        },
    },
    filters: {
        moment: function (date) {
            return moment(date).format("MMM DD YYYY, h:mm a");
        },
        dob: function (date) {
            return moment(date).format("MMMM DD, YYYY");
        }
    },
    computed: {
        activeperiod() {
            return this.activeElectionPeriod.length;
        },
        checkVoted() {
            return this.voted.length;
        },
        assigned_sess() {
            return this.allSess.sort((a, b) => new Date(a.start_time) - new Date(b.start_time)).filter((el) => {
                return this.assignedSess.map((sel) => { return sel.id; }).includes(el.id);
            });
        }
    },
    methods: {
        getPaymentHistory() {
            const id = this.userable_id;
            axios.get(this.apiDomain + `/api/payments/${id}`, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            .then(res => {
                // console.log (res.data)
                this.tableLoading = false;
                this.paymentHistory = res.data;
            })
            .catch(err => {
                // console.log(err)
                this.tableLoading = false;
            });
        },
        getMember() {
            this.fullscreenLoading = true
            axios.get(this.apiDomain + "/api/user", {
                headers: { Authorization: "Bearer " + this.$cookies.get("token") },
            })
            .then((response) => {
                // console.log('getMember', response)
                // var filepath = response.data.profile_pic;
                // if (filepath)
                // 	var fin_filepath = filepath.replace("public", "storage");
                // 	this.profile_pic = fin_filepath;
                // }
                this.userable_type = response.data.userable_type;
                this.user_id = response.data.id;
                this.userable_id = response.data.userable_id;
                // // console.log("getMember res: ", response);
                let d = new Date();
                d.setTime(d.getTime() + 1 * 24 * 60 * 60 * 1000);
                let expires = "expires=" + d.toUTCString();
                
                axios.get(this.apiDomain + `/api/pcr/memberemail?email=${response.data.email}`, {
                    headers: {
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then((response2) => {
                    // // console.log(response2)
                    let member_data = response2.data;
                    this.$cookies.set("member_id", member_data.id, expires, "/");
                    this.member_id = member_data.id;
                    this.conv_url = member_data.conv_url;
                    
                    this.full_name = member_data.first_name + " " + member_data.last_name;
                    this.first_name = member_data.first_name;
                    this.middle_name = member_data.middle_name;
                    this.last_name = member_data.last_name;
                    this.cert_name = member_data.first_name + " " + member_data.middle_name.charAt(0) + ". " + member_data.last_name;
                    this.email = member_data.email;
                    this.date_of_birth = member_data.date_of_birth;
                    this.place_of_practice = member_data.place_of_practice;
                    this.phone_num = member_data.contact_number;
                    this.regDate = moment(member_data.created_at).format("MMMM DD, YYYY");
                    this.prc_number = member_data.prc_number;
                    if (member_data.prc_upload) {
                        this.prc_upload =
                            this.apiDomain + "/" +
                                member_data.prc_upload.replace("public", "storage");
                    }
                    this.prc_expiration = member_data.prc_expiration;
                    this.pma_num = member_data.pma_number;
                    this.can_vote = member_data.can_vote;
                    this.has_requested_cogs = member_data.has_requested_cogs
                    
                    let arr = [];
                    let addressStr = member_data.address;
                    if (addressStr) {
                        arr = addressStr.split(",");
                        this.address.country = arr[2].trim();
                        this.address.region = arr[1].trim();
                        this.address.city = arr[0].trim();
                        this.address.zip = arr[3].trim();
                    }
                    this.is_trainee = member_data.is_trainee;
                    this.membership = member_data.memberships;
                    this.prc_chapter = member_data.chapter_id;
                    this.balance = member_data.balance;
                    this.has_pending_orders = member_data.has_pending_orders;
                    this.can_generate_cert = member_data.can_generate_cert;
                    this.training_ins = member_data.training_ins;
                    this.trainee_status = member_data.is_trainee;
                    this.mem_type_id = member_data.mem_type_id;
                    this.depUpload_id = response.data.userable_id;
                    this.getActiveElectionPeriod(this.member_id);

                    if (member_data.deposit_slip) {
                        this.imageUrl3 =
                            this.apiDomain + "/" +
                                member_data.deposit_slip.replace("public", "storage");
                    }
                    var filepath = member_data.profile_pic;
                    if (filepath) {
                        this.profile_pic = filepath;
                    }
                    
                    this.spec_div_mem_type = member_data.spec_div_mem_type;
                    this.affi_soc_mem_type = member_data.affi_soc_mem_type;
                    this.affi_soc_mem_name = member_data.affi_soc_mem_name;

                    let educational_background = member_data.educational_background;
                    this.edu.medicine = educational_background.medicine;
                    this.edu.med_graduation = educational_background.med_graduation;
                    this.edu.residency = educational_background.residency;
                    this.edu.res_graduation = educational_background.res_graduation;
                    this.edu.specialty = educational_background.specialty;
                    this.edu.spec_graduation = educational_background.spec_graduation;
                    this.edu.specialty_society = educational_background.specialty_society;
                    this.edu.specialty_society_graduation = educational_background.specialty_society_graduation;
                    this.edu.subspecialty = educational_background.subspecialty;
                    this.edu.subspecialty_society = educational_background.subspecialty_society;
                    this.edu.subspecialty_society_induction = educational_background.subspecialty_society_induction;
                    this.edu.master_education = educational_background.master_education;
                    this.edu.master_education_school = educational_background.master_education_school;
                    
                    this.getCollectionFees();
                    this.getAllSessions();
                    this.fullscreenLoading = false
                });
            })
            .catch((error) => {
                console.error(error);
                this.fullscreenLoading = false
            });
        },
        replaceByDefault(e) {
            var img = "/images/noprofile.png";
            e.target.src = img;
        },
        uploadDepositSlip() {
            this.fullscreenLoading = true;
            let formBody = new FormData();
            formBody.append("deposit_slip", this.imageUrl3final);
            axios
                .post(this.apiDomain + `/api/pcr/upload/deposit/${this.member_id}`, formBody, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            .then((res) => {
                this.$message.success("Successfully uploaded.");
                this.fullscreenLoading = false;
                this.image3_change = false;
            })
            .catch((err) => {
                // console.log(err);
                this.$message.error("Something went wrong. Please contact support. " + err);
                this.fullscreenLoading = false;
            });
        },
        uploadProfilePic() {
            this.fullscreenLoading = true;
            let formBody = new FormData();
            formBody.append("profile_pic", this.profile_pic_final);
            formBody.append("user_id", this.user_id);
            if (this.userable_type === "App\\Models\\PCRMember") {
                axios.post(this.apiDomain + `/api/changeProfilePic`, formBody, {
                    headers: {
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then((res) => {
                    this.$message.success("Successfully uploaded.");
                    this.fullscreenLoading = false;
                    this.profile_pic_change = false;
                })
                .catch((err) => {
                    // console.log(err);
                    this.$message.error("Something went wrong. Please contact support. " + err);
                    this.fullscreenLoading = false;
                });
            }
            else {
                this.fullscreenLoading = true;
                axios.post(this.conv_url + "api/changeProfilePic", formBody, {
                    headers: {
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then((res) => {
                    this.$message.success("Successfully uploaded.");
                    this.fullscreenLoading = false;
                    this.profile_pic_change = false;
                })
                .catch((err) => {
                    // console.log(err);
                    this.$message.error("Something went wrong. Please contact support. " + err);
                    this.fullscreenLoading = false;
                });
            }
        },
        updatePRCDetails() {
            this.fullscreenLoading = true
            let formBody = new FormData()
            formBody.append("prc_number", this.prc_number)
            formBody.append("prc_expiration", this.prc_expiration)
            formBody.append("prc_upload", this.prc_image)

            axios.post(this.apiDomain + '/api/pcr/details', formBody, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            .then((res) => {
                this.$message.success(res.data.message)
                this.fullscreenLoading = false
            })
            .catch((err) => {
                console.error(err)
                if(err.response.status == 500) {
                    this.$message.error("The PRC details could not be updated now. Please contact the secretariat.")
                } else {
                    this.$message.error(err.response.data.message)
                }

                this.fullscreenLoading = false
            });
        },
        isEmptyOrSpaces(str) {
            return str === null || str.match(/^ *$/) !== null;
        },
        updateMembersProfile() {
            this.fullscreenLoading = true;
            this.$v.$touch();
            if (!this.$v.$invalid) {
                let newAddress = this.address.city +
                    ", " +
                    this.address.region +
                    ", " +
                    this.address.country +
                    ", " +
                    this.address.zip;
                let data = {
                    first_name: this.first_name,
                    middle_name: this.middle_name,
                    last_name: this.last_name,
                    date_of_birth: this.date_of_birth,
                    address: newAddress,
                    is_trainee: this.trainee_status,
                    chapter_id: this.chapter_id,
                    mem_type_id: this.mem_type_id,
                    
                    medicine: this.edu.medicine,
                    med_graduation: this.edu.med_graduation,
                    residency: this.edu.residency,
                    res_graduation: this.edu.res_graduation,
                    specialty: this.edu.specialty,
                    spec_graduation: this.edu.spec_graduation,
                    specialty_society: this.edu.specialty_society,
                    specialty_society_graduation: this.edu.specialty_society_graduation,
                    subspecialty: this.edu.subspecialty,
                    subspecialty_society: this.edu.subspecialty_society,
                    subspecialty_society_induction: this.edu.subspecialty_society_induction,
                    master_education: this.edu.master_education,
                    master_education_school: this.edu.master_education_school,
                    spec_div_mem_type: this.spec_div_mem_type,
                    affi_soc_mem_type: this.affi_soc_mem_type,
                };
                axios.put(this.apiDomain + `/api/pcr/member/${this.member_id}`, data, {
                    headers: {
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then((res) => {
                    this.$notify({
                        message: "Member`s Profile successfully updated.",
                        type: "success",
                        position: "bottom-right",
                    });
                    this.fullscreenLoading = false;
                })
                .catch((err) => {
                    // console.log(err);
                    this.$message.error("Something went wrong. Please contact support. " + err);
                    this.fullscreenLoading = false;
                });
            }
            else {
                this.fullscreenLoading = false;
                this.$notify({
                    message: "Missing or incorrect fields, please fill in all fields properly",
                    type: "error",
                    position: "bottom-right",
                });
            }
        },
        gotoConvReg() {
            // let conventionUrl = process.env.MIX_URL_CONV+'registration?id='+this.member_id;
            // window.open(conventionUrl);
            if (this.subdomain == "beta2") {
                // location.href =
                // 	"https://beta2.73rdpcrannualconvention.com/registration?id=" +
                //     this.member_id;
                window.open("https://beta2.73rdpcrannualconvention.com/registration?id=" +
                    this.member_id);
            }
            else {
                // location.href =
                // 	"https://73rdpcrannualconvention.com/registration?id=" +
                // 	this.member_id;
                window.open("https://73rdpcrannualconvention.com/registration?id=" +
                    this.member_id);
            }
        },
        addAttachment3(file, fileList) {
            this.attachments3.push(file);
        },
        addAttachment4(file, fileList) {
            this.attachments4.push(file);
        },
        beforeAvatarUpload(file) {
            const isPIC = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isPIC) {
                this.$message.error("Profile photo must be an image filetype.");
            }
            if (!isLt2M) {
                this.$message.error("Profile photo cannot exceed 2MB.");
            }
        },
        beforeAvatarUpload2(file) {
            const isPIC = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isPIC) {
                this.$message.error("Profile photo must be an image filetype.");
            }
            if (!isLt2M) {
                this.$message.error("Profile photo cannot exceed 2MB.");
            }
        },
        handleDepositSuccess(file) {
            // console.log(file);
            this.imageUrl3 = URL.createObjectURL(file.raw);
        },
        handleDepositSuccess2(file) {
            // console.log(file);
            this.profile_pic = URL.createObjectURL(file.raw);
        },
        handleImageUpload3(f, filelist) {
            this.fullscreenLoading = true;
            // STORES IMAGE IN FILE INPUT THEN STORES THE NAME OF THE FILE IN THE TEXT INPUT FOR VALIDATION
            let file = f;
            this.validateFile(file.raw, "photo", (result) => {
                this.fullscreenLoading = false;
                if (!result) {
                    file = null;
                }
                this.imageUrl3 = file.raw;
                this.image3_change = true;
                if (this.imageUrl3) {
                    var reader = new FileReader();
                    // this.$v.image3.$model = this.imageUrl3
                    this.image3 = this.imageUrl3;
                    reader.onload = (e) => {
                        this.imageUrl3 = e.target.result;
                    };
                    reader.readAsDataURL(this.imageUrl3);
                }
                else {
                    this.image3 = "";
                }
            });
            // this.step3.imageUrl3final = f.raw
            this.imageUrl3final = f.raw;
        },
        handleImageUpload4(f, filelist) {
            this.fullscreenLoading = true;
            // STORES IMAGE IN FILE INPUT THEN STORES THE NAME OF THE FILE IN THE TEXT INPUT FOR VALIDATION
            let file = f;
            this.validateFile(file.raw, "photo", (result) => {
                this.fullscreenLoading = false;
                if (!result) {
                    file = null;
                }
                this.profile_pic = file.raw;
                this.profile_pic_change = true;
                if (this.profile_pic) {
                    var reader = new FileReader();
                    // this.$v.image3.$model = this.imageUrl3
                    this.image4 = this.profile_pic;
                    reader.onload = (e) => {
                        this.profile_pic = e.target.result;
                    };
                    reader.readAsDataURL(this.profile_pic);
                }
                else {
                    this.image4 = "";
                }
            });
            // this.step3.imageUrl3final = f.raw
            this.profile_pic_final = f.raw;
        },
        onChangePRCImage(file) {
            this.validateFile(file.raw, "photo", (result) => {
                if (result == true) {
                    this.prc_upload = file.raw
                    if (this.prc_upload) {
                        var reader = new FileReader();
                        reader.onload = (e) => {
                            this.prc_upload = e.target.result;
                        };
                        reader.readAsDataURL(this.prc_upload);
                    } else {
                        this.prc_upload = "";
                    }                    
                }
            });

            this.prc_image = file.raw;
        },
        validateFile(file, type, callback) {
            // VALIDATE IMAGE FILE
            let valid_types = this.image_types;
            if (!file) {
                callback(false);
            }
            else if (file.size > this.file_max) {
                this.$message.error("File is too big!");
                callback(false);
            }
            //   else if(!valid_types.includes(file.type)) {
            //     this.$message.error('Invalid file type');
            //     callback(false)
            //   }
            else {
                callback(true);
            }
        },
        getMemberTypes() {
            axios.get(this.apiDomain + "/api/membershiptype")
            .then((res) => {
                this.memberTypes = res.data;
            })
            .catch((err) => {
                console.log(err);
            });
        },
        getChapterTypes() {
            axios.get(this.apiDomain + "/api/chaptertypes")
            .then((res) => {
                this.chapterTypes = res.data;
            })
            .catch((err) => {
                console.log(err);
            });
        },
        getSpecialtyDivisionMembershipTypes() {
            axios.get(this.apiDomain + "/api/specialty-divisions")
            .then((res) => {
                this.specDivTypes = res.data;
            })
            .catch((err) => {
                console.log(err);
            });
        },
        getAffiliateSocietyMembershipTypes() {
            axios.get(this.apiDomain + "/api/affiliate-societies")
            .then((res) => {
                this.affiSocTypes = res.data;
            })
            .catch((err) => {
                console.log(err);
            });
        },
        changeValue(data) {
            // console.log(data);
        },
        changeMembershipValue(data) {
            this.mem_type_id_string = data.join("-");
        },
        gotoElection() {
            this.$router.push("election");
        },
        getActiveElectionPeriod(memberID) {
            this.checkActiveElection();
            axios.get(this.apiDomain + "/api/election/active/profile", {
                headers: { Authorization: "Bearer " + this.$cookies.get("token"), }
            })
            .then(res => {
                if (res.data) {
                    this.activeElectionPeriod = res.data;
                    if (res.data.length >= 1) {
                        this.checkVoteStatus(this.activeElectionPeriod[0].id, memberID);
                    }
                    //// console.log(this.$cookies.get("token"));
                }
            })
            .catch(err => {
                console.log(err);
            });
        },
        checkActiveElection() {
            axios.get(this.apiDomain + "/api/election/checkActive", {
                headers: { Authorization: "Bearer " + this.token }
            })
            .then(res => {
                if (res.data) {
                    //// console.log(res.data);
                }
            })
            .catch(err => {
                console.log(err);
            });
        },
        goToMeeting() {
            var url = "https://us02web.zoom.us/meeting/register/tZ0kdOuppzgsHdeVG3qfT0Za_muxRBmy9YwS";
            var target = "_blank";
            window.open(url, target);
        },
        checkVoteStatus(election, member) {
            axios.get(this.apiDomain + "/api/election/checkVoteStatus/" + member + "/" + election, {
                headers: { Authorization: "Bearer " + this.$cookies.get("token"), }
            })
            .then((res) => {
                if (res.data) {
                    this.voted = res.data;
                }
            })
            .catch((err) => {
                console.log(err);
            });
        },
        getAllSessions() {
            axios.get(this.apiDomain + `/api/session`, {
                headers: { Authorization: "Bearer " + this.token }
            })
            .then(res => {
                if (res.data) {
                    this.allSess = res.data.data;
                    this.getAllUserSessions();
                }
            })
            .catch(err => {
                console.log(err);
            });
        },
        getAllUserSessions() {
            axios.get(this.apiDomain + `/api/session/user/${this.user_id}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
            .then(res => {
                if (res.data) {
                    this.mySess = res.data.data;
                    this.mySess.forEach(element => {
                        this.assignedSess.push({ id: element.session_id });
                    });
                }
            })
            .catch(err => {
                console.log(err);
            });
        },
        getCollectionFees() {
            this.$refs.paymentList.listLoading = true

            let data = {
                user_id: this.user_id
            }

            axios.post(this.apiDomain + '/api/orders/user/dashboard', data, { headers: { Authorization: "Bearer " + this.$cookies.get("token"), }, })
            .then(res => {
                this.top_annual_orders = res.data.top_annual_orders
                this.previous_orders = res.data.previous_orders;
                this.$refs.paymentList.listLoading = false
            })
            .catch(err => {
                console.log(err)
                this.$refs.paymentList.listLoading = false
            });
        },
        viewPaymentHistory() {
            this.payHistDialog = true;

            const id = this.userable_id;
            axios.get(this.apiDomain + `/api/payments/${id}`, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            .then(res => {
                // console.log (res.data)
                this.tableLoading = false;
                this.paymentHistory = res.data;
            })
            .catch(err => {
                // console.log(err)
                this.tableLoading = false;
            });
        },
        viewIdeapayVideo() {
            this.ideapayVideo = true;
        },
        viewDepositSlipVideo() {
            this.depositSlip = true;
        },
        joinMiniSessVid(id, url) {
            var target = "_blank";
            window.open(url, target);
        },
        bindPusher() {
            console.log("Binding Pusher...");
            var self = this;
            var channel = pusher.subscribe('mdmsi');
            channel.bind("order-fee", function() {
                self.getCollectionFees(this.member_id)
            });
        },
        checkUser() {
            if(!this.$cookies.get("token")) {
                this.$cookies.remove('member_id')
                this.$cookies.remove('user_role')
                location.href = "/login"
            } else {
                let subdomain = location.hostname.split(".").shift();
                this.subdomain = subdomain;

                this.getMemberTypes()
                this.getChapterTypes()
                this.getSpecialtyDivisionMembershipTypes()
                this.getAffiliateSocietyMembershipTypes()
                this.getMember()
                this.bindPusher();
            }
        },
    },
    mounted() {
        this.checkUser()
    }
};
</script>

<style lang="scss" scoped>
button.el-button.reg-goto-btn.is-disabled {
    background-color: rgb(190, 190, 190) !important;
}
.page-site-profile .page-header {
    padding-top: 80px;
    margin-top: 0;
    margin-bottom: 0;
}
.page-header .page-site-profile-header {
    background-image: url(/images/layout/pcr-site-reg-bg.png);
    background-repeat: no-repeat;
    background-size: contain;
    width: 100%;
    height: 0;
    padding-top: 26.0417%;
    position: relative;
}
.page-site-profile-header-text {
    position: absolute;
    width: 100%;
    bottom: 0;
    top: 42%;
    text-align: center;
    font-size: 2.2rem;
    color: #fff;
    font-weight: bold;
}
.pcr-page-title {
    font-size: 24px;
    padding-top: 50px;
}
.pcr-page-subtitle {
    font-size: 18px;
}
.profile-card h1 {
    font-size: 24px;
    text-align: center;
}
.sec-facebook-info img {
    height: 35px;
    width: 35px;
}
.reg-goto-btn {
    background-color: #00005c !important;
    color: #fff !important;
    border: none !important;
    white-space: normal!important;
    line-height: normal!important;
    
}
.page-sponsors {
    padding: 30px;
    margin-right: auto;
    margin-left: auto;
    background-color: #f2f2f2;
}
.page-sponsors img {
    max-width: 100%;
    max-height: 50px;
    display: inline-block;
}
.invalid-field {
    color: red;
}
.profile-card .el-input.is-disabled .el-input__inner,
.profile-card .el-textarea.is-disabled .el-textarea__inner {
    background-color: #fff;
    border-color: #c0c4cc;
    color: #606266;
}
.sec-profile-photo {
    text-align: center;
    margin-bottom: 1.5rem;
}
.sec-profile-photo img {
    border-radius: 500px;
    max-width: 180px;
}
.page-site-reg .page-header {
    padding-top: 80px;
    margin-top: 0;
    margin-bottom: 0;
}
.page-header .page-site-reg-header {
    background-image: url(/images/layout/pcr-site-reg-bg.png);
    background-repeat: no-repeat;
    background-size: contain;
    width: 100%;
    height: 0;
    padding-top: 26.0417%;
    position: relative;
}
.page-site-reg-header-text {
    position: absolute;
    width: 100%;
    bottom: 0;
    top: 42%;
    text-align: center;
    font-size: 2.2rem;
    color: #fff;
    font-weight: bold;
}
.pcr-page-title {
    font-size: 24px;
    padding-top: 50px;
}
.pcr-page-subtitle {
    font-size: 18px;
}
.registration-card h1 {
    font-size: 24px;
    text-align: center;
}
.sec-facebook-info img {
    height: 35px;
    width: 35px;
}
.reg-submit-btn {
    width: 100%;
}
.invalid-field {
    color: red;
}
.sec-photo {
    text-align: center;
}
.sec-photo .avatar-uploader .el-upload {
    border-radius: 500px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.sec-photo .avatar-uploader .el-upload:hover {
    background: #409eff;
}
.sec-photo .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 100px;
    height: 100px;
    line-height: 100px;
    text-align: center;
    background: #dddddd;
}
.sec-photo .avatar-uploader .el-upload:hover .avatar-uploader-icon {
    color: #ffffff;
}
.sec-photo .avatar {
    width: 100px;
    height: 100px;
    display: block;
    margin: 0 !important;
}
.sec-photo .avatar-uploader.ava-icon-square .el-upload {
    border-radius: 0;
}
.meeting-cont {
    margin-top:20px;
    font-size:1.1rem;
    padding:20px;
    background:#f8f8f8;
}
.meeting-cont b {
    font-weight:bold;
}
.sess-cont {
    padding:0 0 20px;
    border-bottom:20px solid #f2f2f2;
}	
.sess-cont h4 {
    padding-bottom:10px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}
.accordion-title{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
    padding-right: 1em;
    align-items: center; 
}
.accordion-title p{
    margin:0px;
}
.accordion-title:hover{
    color: #409eff;
}
.sec-personal-info p{
    color: #606266;
}
.mw-50 {
    max-width: 50%;
}
.con-pcr-image {
    border: 1px solid #c0c4cc;
    border-radius: 4px;
}
.icon-pcr-id {
    background: #dddddd;
    font-size: 50px;
    padding: 20px;
    margin: 5px;
}
.con-how-to {
    &::v-deep {
        .el-icon-video-play {
            font-size: 30px!important;
        }
        .el-button {
            font-size: 15px!important;
            span {
                position: relative;
                top: -5px;
            }
        }
    }
}

@media only screen and (max-width: 768px) {
    .el-dialog__wrapper .el-dialog{
        width: 80% !important;
        .el-dialog__header{
            padding: 0;
            .el-dialog__headerbtn{
                position: absolute;
                .el-dialog__close{
                    padding: .5em;
                }
            }
        }
        .el-dialog__body {
            height: 100% !important;
        }
    }
}

@media only screen and (max-width: 475px) {
    .el-select-dropdown {
        &.el-popper {
            max-width: 320px;
            .el-select-dropdown__item {
                span {
                    white-space: normal;
                }
            }
        }
    }

    .on-mob{
        flex-direction: column !important;
        .el-button{
            width: 100% !important;
        }
    }
}
</style>