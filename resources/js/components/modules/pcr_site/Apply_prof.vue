<template>
	<transition name="el-zoom-in-top">
		<div class="content-wrapper page-site-reg" id="apply-page" v-loading.fullscreen.lock="fullscreenLoading">
			<!-- Main content -->
			<section class="page-header">
				<div class="page-site-apply-header">
					<div class="page-site-apply-header-text">
						Application for Online Membership
					</div>
				</div>
			</section>

			<section class="content">
				<div class="d-flex justify-content-center">
					<el-card class="box-card registration-card my-5">
						<div slot="header" class="clearfix">
							<h1>Application</h1>
						</div>
						<form v-loading="loading">
							<div class="row">
								<div class="sec-photo col-12">
									<el-upload
										class="avatar-uploader"
										action="https://jsonplaceholder.typicode.com/posts/"
										:http-request="addAttachment"
										:auto-upload="false"
										:show-file-list="false"
										:on-success="handleAvatarSuccess"
										accept="image/png, image/jpeg, image/jpg"
										:before-upload="beforeAvatarUpload"
										:on-change="handleImageUpload"
										:file-list="attachments">
										<img v-if="imageUrl" :src="imageUrl" class="avatar" />
										<i v-else class="el-icon-camera-solid avatar-uploader-icon"></i>
									</el-upload>
									<div class="mt-3 mb-5">
										Click above to provide<br />Profile Photo
									</div>
								</div>

								<div class="col-md-6 col-sm-12">
									<div class="sec-personal-info">
										<p class="mb-0 font-weight-bold">
											Personal Information
										</p>
										<p class="note mb-2">*Please follow PRC Data</p>
										<div v-if="$v.step1.last_name.$dirty">
											<div class="invalid-field" v-if="!$v.step1.last_name.required">
												Required
											</div>
										</div>
										<el-input class="mb-3 name-uppercase" placeholder="Last Name" v-model="$v.step1.last_name.$model" />

										<div v-if="$v.step1.first_name.$dirty">
											<div class="invalid-field" v-if="!$v.step1.first_name.required">
												Required
											</div>
										</div>
										<el-input class="mb-3 name-uppercase" placeholder="First Name" v-model="$v.step1.first_name.$model" />

										<div v-if="$v.step1.middle_name.$dirty">
											<div class="invalid-field" v-if="!$v.step1.middle_name.required">
												Required
											</div>
										</div>
										<el-input class="mb-3 name-uppercase" placeholder="Middle Name" v-model="$v.step1.middle_name.$model" />

										<div class="dob">
											<el-date-picker class="mb-3 w-100" v-model="$v.step1.birthday.$model" type="date"
												value-format="yyyy-MM-dd" placeholder="Date of Birth (Year-Month-Day)" @change="setDOB" />								
										</div>

										<div v-if="$v.step1.email.$dirty">
											<div class="invalid-field" v-if="!$v.step1.email.required">
												Required
											</div>
											<div class="invalid-field" v-if="!$v.step1.email.email">
												Incorrect email format
											</div>
										</div>
										<el-input class="mb-3" placeholder="Email Address" type="email" v-model="$v.step1.email.$model" />

										<div v-if="$v.step1.contact_num.$dirty">
											<div class="invalid-field" v-if="!$v.step1.contact_num.required">
												Required
											</div>
											<div class="invalid-field" v-if="!$v.step1.contact_num.integer">
												Incorrect phone format
											</div>
										</div>
										<el-input class="mb-3" placeholder="Contact Number" v-model="$v.step1.contact_num.$model" />
									</div>
									<div class="sec-profession mt-3 mb-4">
										<p class="mb-3 font-weight-bold">
											Profession
										</p>
										<div v-if="$v.profession.prof_type_id.$dirty">
											<div class="invalid-field" v-if="!$v.profession.prof_type_id.required">
												Required
											</div>
										</div>										
										<el-select v-model="$v.profession.prof_type_id.$model" filterable class="w-100" placeholder="Select">
											<el-option
												v-for="option in memberTypes"
												:key="option.id"
												:label="option.type_name"
												:value="option.id" />
										</el-select>														
									</div>
								</div>


								<div class="col-md-6 col-sm-12">
									<p class="mb-3 font-weight-bold">
										PRC Number
									</p>
									<div v-if="$v.step1.prc_num.$dirty">
										<div class="invalid-field" v-if="!$v.step1.prc_num.required">
											Required
										</div>
										<div class="invalid-field" v-if="!$v.step1.prc_num.integer">
											Incorrect PRC format
										</div>
									</div>
									<el-input class="mb-3" placeholder="PRC Number" v-model="$v.step1.prc_num.$model" />

									<div class="sec-photo text-left mt-3 mb-4">
										<p class="mb-3 font-weight-bold">Upload PRC ID Image</p>
										<div v-if="$v.step1.last_name.$dirty">
											<div class="invalid-field" v-if="!$v.step3.imageUrl3final.required">
												Required
											</div>
										</div>					
										<el-upload
											class="avatar-uploader ava-icon-square"
											action="https://jsonplaceholder.typicode.com/posts/"
											:http-request="addAttachment3"
											:auto-upload="false"
											:show-file-list="false"
											:on-success="handlePrcIDSuccess"
											:before-upload="beforeAvatarUpload"
											:on-change="handleImageUpload3"
											accept="image/png, image/jpeg, image/jpg"
											:file-list="attachments3">
											<img v-if="imageUrl3" :src="imageUrl3" class="avatar">
											<i v-else class="el-icon-camera-solid avatar-uploader-icon"></i>
										</el-upload>
										<div class="mt-2 font-italic" style="font-size:0.8rem;">
											Please upload a digital copy of your PRC ID by clicking above
										</div>
									</div>

									<div>
										<p class="mb-3 font-weight-bold">
											PRC ID Expiration Date
										</p>
										<div v-if="$v.step1.prc_expi.$dirty">
											<div class="invalid-field" v-if="!$v.step1.prc_expi.required">
												Required
											</div>
										</div>					
										<el-date-picker class="mb-3 w-100" v-model="$v.step1.prc_expi.$model" type="date"
											value-format="yyyy-MM-dd" placeholder="Date of Expiration" @change="setPRCExp" />
									</div>	

									<p class="mb-0 font-weight-bold">
										PMA Number
									</p>
									<p class="note mb-2">If new applicant w/o this number, please write NEW and we will supply the number soonest.</p>
									<div v-if="$v.step1.pma_num.$dirty">
										<div class="invalid-field" v-if="!$v.step1.pma_num.required">
											Required
										</div>
										<!--<div
											class="invalid-field"
											v-if="!$v.step1.pma_num.integer"
										>
											Incorrect PMA format
										</div>-->
									</div>								
									<el-input class="mb-3" placeholder="PMA Number" v-model="$v.step1.pma_num.$model" />
									<!-- <div class="sec-mailing-info mt-3">
										<p class="mb-3 font-weight-bold">Mailing Information</p>
										<div v-if="$v.step2.mailing_info.$dirty">
											<div class="invalid-field" v-if="!$v.step2.mailing_info.required">Required</div>
										</div>
										<el-input class="mb-3" type="textarea" autosize
											placeholder="Number / Street / Town / City / Municipality / Province / Postal code"
											v-model="$v.step2.mailing_info.$model">
										</el-input>
									</div> -->
								</div>

								<div class="col-md-6 col-sm-12 mt-3">
									<div class="sec-mailing-info">
										<p class="mb-3 font-weight-bold">
											Hospital/Clinic Address <span class="required-item">*</span>
										</p>
										<div v-if="$v.address.country.$dirty">
											<div class="invalid-field" v-if="!$v.address.country.required">
												Required
											</div>
										</div>
										<country-select v-model="$v.address.country.$model" :country="$v.address.country.$model"
											className="country-select el-input__inner mb-3" :countryName="true" defaultRegion="PH"
										/>

										<div v-if="$v.address.region.$dirty">
											<div class="invalid-field" v-if="!$v.address.region.required">
												Required
											</div>
										</div>
										<region-select v-model="$v.address.region.$model" :country="$v.address.country.$model" className="region-select el-input__inner mb-3"
											:countryName="true" :regionName="true" :region="$v.address.region.$model" 
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

									<div class="sec-membership-info">
										<p class="mb-3 font-weight-bold">
											Main Hospital Affiliation
										</p>
										<el-input class="mb-3" placeholder="Main Hospital Affiliation" v-model="$v.address.practice.$model" />
									</div>

									<!--<div class="sec-membership-info mt-4">
										<p class="mb-3 font-weight-bold">
											Resident in Training
										</p>
										<el-radio-group v-model="trainee_status" @input="changeValue">
											<p>
												<el-radio :label="1">Yes</el-radio>
											</p>
											<p>
												<el-radio :label="0">No</el-radio>
											</p>
										</el-radio-group>
									</div>
									<div class="sec-membership-info mt-4" v-if="trainee_status === 1" >
										<p class="mb-3 font-weight-bold">
											Training Institution
										</p>
										<el-input class="mb-3" placeholder="Name of training institution" v-model="training_ins" />
									</div> -->

									<div class="sec-membership-info mt-2" v-if="trainee_status !== 1" >
										<p class="mb-3 font-weight-bold">Membership</p>
										<div v-if="$v.membership.mem_type_id.$dirty">
											<div class="invalid-field" v-if="!$v.membership.mem_type_id.required">
												Required
											</div>
										</div>
										<el-select v-model="$v.membership.mem_type_id.$model" filterable class="w-100" placeholder="Select">
											<el-option
												v-for="option in memberTypes"
												:key="option.id"
												:label="option.type_name"
												:value="option.id" />
										</el-select>
									</div>

									<div class="my-4">
										<p class="mb-3 font-weight-bold">Specialty Division Membership</p>
										<el-select v-model="spec_div_mem_type" filterable class="w-100" placeholder="Select">
											<el-option label="None" :value="null" />
											<el-option
												v-for="option in specDivTypes"
												:key="option.id"
												:label="option.name"
												:value="option.id" />
										</el-select>
									</div>

									<div class="my-4">
										<p class="mb-3 font-weight-bold">Affiliate Society Membership</p>
										<el-select v-model="affi_soc_mem_type" filterable class="w-100" placeholder="Select">
											<el-option label="None" :value="null" />
											<el-option
												v-for="option in affiSocTypes"
												:key="option.id"
												:label="option.name"
												:value="option.id" />
											<el-option label="Others" :value="0" />
										</el-select>

										<div v-if="affi_soc_mem_type == 0" class="mt-2">
											<div v-if="$v.membership.affi_soc_other.$dirty" class="mt-2">
												<div class="invalid-field" v-if="!$v.membership.affi_soc_other.required">
													Required
												</div>
											</div>
											<el-input v-model="$v.membership.affi_soc_other.$model" placeholder="Enter affiliate here" />
										</div>
									</div>

									<!--<div class="sec-prc-chapter-info mt-4" v-if="trainee_status !== 1">
										<p class="mb-3 font-weight-bold">PCR Chapter</p>
										<el-radio-group v-model="chapter_id" @input="changeValue">
											<p v-for="chaptertype in chapterTypes" :key="chaptertype.id">
												<el-radio :label="chaptertype.id">
													{{ chaptertype.chapter_name }}
												</el-radio>
											</p>
										</el-radio-group>
									</div>-->
								</div>

								<div class="col-md-6 col-sm-12 mt-3">
									<p class="mb-3 font-weight-bold">Educational Background</p>
									<div class="row mb-3">
										<div class="col-12 mb-2">
											<p class="mb-1">College of Medicine</p>
											<el-input placeholder="College Medicine" v-model="edu.medicine"></el-input>
										</div>
										<div class="col-12">
											<p class="mb-1">Graduation</p>
											<el-date-picker class="w-100" v-model="edu.med_graduation" type="year" placeholder="Graduation" />
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-12">
											<p class="mb-1">Specialty</p>
											<el-input placeholder="Specialty" v-model="edu.specialty"></el-input>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-12">
											<p class="mb-1">Specialty Society</p>
											<el-input placeholder="Specialty Society" v-model="edu.specialty_society"></el-input>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-12">
											<p class="mb-1">Subspecialty</p>
											<el-input placeholder="Subspecialty" v-model="edu.subspecialty"></el-input>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-12">
											<p class="mb-1">Subspecialty Society</p>
											<el-input placeholder="Subspecialty Society" v-model="edu.subspecialty_society"></el-input>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-12 mb-2">
											<p class="mb-1">Master's Education</p>
											<el-input placeholder="Master's Education" v-model="edu.master_education"></el-input>
										</div>
										<div class="col-12">
											<p class="mb-1">School</p>
											<el-input placeholder="School" v-model="edu.master_education_school"></el-input>
										</div>
									</div>
								</div>

								<div class="col-12 sec-password">
									<label>
										<el-checkbox v-model="isCeritified" class="mr-2" />
										I hereby certify that the above information is true and correct. Under RA10173, all personal
										information collected, stored and processed by IdeaHub, Inc shall be dutybound to observe
										and respect your privacy rights.
									</label>
									<p class="mb-3 font-weight-bold">Password</p>
									<div v-if="$v.step3.password.$dirty">
										<div class="invalid-field" v-if="!$v.step3.password.required">
											Required
										</div>
									</div>
									<el-input class="mb-3" placeholder="Create Password" v-model="$v.step3.password.$model" show-password />

									<div v-if="$v.step3.conf_password.$dirty">
										<div class="invalid-field" v-if="!$v.step3.conf_password.required">
											Required
										</div>
										<div class="invalid-field" v-if="!$v.step3.conf_password.sameAsPassword">
											Should be same with the password
										</div>
									</div>
									<el-input class="mb-3" placeholder="Confirm Password" v-model="$v.step3.conf_password.$model" show-password />
								</div>

								<div class="col-12 sec-submit mt-4 text-center">
									<el-button class="btn-submit" type="primary" round @click="submit">
										SUBMIT
									</el-button>
									<div class="text-center mt-3">
										Already registered? Please
										<el-button type="text" @click="$router.push('login')" class="font-weight-bold">
											login here
										</el-button>.
									</div>
								</div>
							</div>
						</form>
					</el-card>
				</div>
			</section>
		</div>
	</transition>
</template>

<script>
	import { required, requiredIf, integer, email, sameAs } from "vuelidate/lib/validators/";
	export default {
		name: "Apply",
		data() {
			return {
				apiDomain: this.$store.state.apiUrl,
				isCeritified: false,
				regTypes: [],
				memberTypes: [],
				pressions: [],
				nonmemberTypes: [],
				chapterTypes: [],
				specDivTypes: [],
				affiSocTypes: [],
				step1: {
					last_name: "",
					first_name: "",
					middle_name: "",
					birthday: "",
					prc_expi: "",
					email: "",
					contact_num: "",
					prc_num: "",
					pma_num: ""
				},
				step2: {
					mailing_info: "",
				},
				step3: {
					// username: '',
					imageUrl3final: "",
					password: "",
					conf_password: "",
				},
				image: null,
				reg_type_id: null,
				membership: {
					mem_type_id: null,
					affi_soc_other: null,
				},
				profession: {
					prof_type_id: null,
				},
				mem_type_id_string: [],
				spec_div_mem_type: null,
				affi_soc_mem_type: null,
				non_type_id: null,
				chapter_id: 8,
				training_ins: null,

				step: 1,
				showsubmit: false,
				loading: false,
				trainee_status: 0,
				imageUrl: "",
				imageUrlfinal: "",
				imageUrl3: "",
				
				attachments: [],
				attachments3: [],

				image_types: ["image/png", "image/jpg", "image/jpeg"],
				file_max: 10000000,
				address: {
					country: "Philippines",
					region: "",
					city: "",
					zip: "",
					practice: ""
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
				fullscreenLoading: false,
				populated: false,
			};
		},
		validations: {
			step1: {
				last_name: { required },
				first_name: { required },
				email: { required, email },
				middle_name: { required },
				birthday: { required },
				prc_expi: { required },
				contact_num: { required, integer },
				prc_num: { required, integer },
				pma_num: { required }
			},
			// step2: {
			//   mailing_info: { required },
			// },
			step3: {
				// username: { required },
				password: { required },
				conf_password: { required, sameAsPassword: sameAs("password") },
				imageUrl3final: { required },
			},
			image: {},
			image3: {},
			address: {
				country: { required },
				region: { required },
				city: { required },
				zip: { required },
				practice: { required },
			},
			membership: {
				mem_type_id: { required },
				affi_soc_other: {
					required: requiredIf(function () {
						return (this.affi_soc_mem_type == 0);
					}),
				}
			},
			profession: {
				prof_type_id: { required },
			}
		},
		methods: {
			handleImageUpload(f, filelist) {
				// STORES IMAGE IN FILE INPUT THEN STORES THE NAME OF THE FILE IN THE TEXT INPUT FOR VALIDATION
				let file = f;
				this.validateFile(file.raw, "photo", (result) => {
					if (!result) {
						file = null;
					}

					this.imageUrl = file.raw;
					if (this.imageUrl) {
						var reader = new FileReader();
						this.$v.image.$model = this.imageUrl;

						reader.onload = (e) => {
							this.imageUrl = e.target.result;
						};
						reader.readAsDataURL(this.imageUrl);
					} else {
						this.image = "";
					}
				});
				this.imageUrlfinal = f.raw;
			},
			handleImageUpload3(f, filelist) {
				// STORES IMAGE IN FILE INPUT THEN STORES THE NAME OF THE FILE IN THE TEXT INPUT FOR VALIDATION
				let file = f;
				this.validateFile(file.raw, "photo", (result) => {
					if (!result) {
						file = null;
					}

					this.imageUrl3 = file.raw;
					if (this.imageUrl3) {
						var reader = new FileReader();
						this.$v.image3.$model = this.imageUrl3;

						reader.onload = (e) => {
							this.imageUrl3 = e.target.result;
						};
						reader.readAsDataURL(this.imageUrl3);
					} else {
						this.image3 = "";
					}
				});
				this.step3.imageUrl3final = f.raw;
			},
			checkuser() {
				axios
					.get(
						this.apiDomain + "/api/checkuser?email=" +
							this.email +
							"&prc_number=" +
							this.prc_num
					)
					.then((res) => {
						if (
							res.data.message === "User exists in master list" &&
							res.data.is_trainee === 0
						) {
							// console.log("member");
							this.reg_type_id = 1;
						} else if (
							res.data.message === "User exists in master list" &&
							res.data.is_trainee === 1
						) {
							// console.log("trainee");
							this.reg_type_id = 2;
						} else if (res.data.message === "User is a non member") {
							// console.log("non member");
							this.reg_type_id = 3;
						}
					})
					.catch((err) => {
						// console.log(err);
					});
			},
			getRegTypes() {},
			validateFile(file, type, callback) {
				// VALIDATE IMAGE FILE
				let valid_types = this.image_types;
				if (!file) {
					callback(false);
				} else if (file.size > this.file_max) {
					this.$message.error("File is too big!");
					callback(false);
				} else if (!valid_types.includes(file.type)) {
					this.$message.error("Invalid file type");
					callback(false);
				} else {
					callback(true);
				}
			},
			submit() {
				this.$v.$touch();

				if(!this.$v.$invalid) {
					var fname = this.step1.first_name.toUpperCase()
					var mname = this.step1.middle_name.toUpperCase()
					var lname = this.step1.last_name.toUpperCase()
					// // console.log(fname + mname + lname)
					const memData = new FormData();
					memData.append("email", this.step1.email);
					memData.append("password", this.step3.password);
					memData.append("first_name", fname);
					memData.append("middle_name", mname);
					memData.append("last_name", lname);
					memData.append("date_of_birth", this.step1.birthday);
					memData.append("prc_number", this.step1.prc_num);
					memData.append("pma_number", this.step1.pma_num);
					memData.append("contact_number", this.step1.contact_num);
					// memData.append('address', this.step2.mailing_info)
					let addresss =
						this.address.city +
						", " +
						this.address.region +
						", " +
						this.address.country +
						", " +
						this.address.zip;
					memData.append("address", addresss);
					memData.append("place_of_practice", this.address.practice);
					memData.append("mem_type_id", this.membership.mem_type_id);
					if(this.spec_div_mem_type !== null) {
						memData.append("spec_div_mem_type", this.spec_div_mem_type);
    		        }					
					if(this.affi_soc_mem_type !== null && this.affi_soc_mem_type !== 0) {
						memData.append("affi_soc_mem_type", this.affi_soc_mem_type);
    		        } else {
						memData.append("affi_soc_other", this.membership.affi_soc_other);
					}
					memData.append("chapter_id", this.chapter_id);
					memData.append("is_trainee", this.trainee_status);
					// memData.append('prc_id', null)
					// memData.append("memberships", this.mem_type_id_string);
					memData.append("profile_pic", this.imageUrlfinal);
					memData.append("training_ins", this.training_ins);
					memData.append("prc_expiration", this.step1.prc_expi);
					memData.append("prc_upload", this.step3.imageUrl3final);
					memData.append("tag_id", 1);
					memData.append("medicine", this.edu.medicine);
					memData.append("med_graduation", this.edu.med_graduation);
					memData.append("residency", this.edu.residency);
					memData.append("res_graduation", this.edu.res_graduation);
					memData.append("specialty", this.edu.specialty);
					memData.append("spec_graduation", this.edu.spec_graduation);
					memData.append("specialty_society", this.edu.specialty_society);
					memData.append("specialty_society_graduation", this.edu.specialty_society_graduation);
					memData.append("subspecialty", this.edu.subspecialty);
					memData.append("subspecialty_society", this.edu.subspecialty_society);
					memData.append("subspecialty_society_induction", this.edu.subspecialty_society_induction);
					memData.append("master_education", this.edu.master_education);
					memData.append("master_education_school", this.edu.master_education_school);

					if(this.isCeritified) {
						this.loading = true;
						axios.post(this.apiDomain + "/api/members", memData, {
							headers: {
								"Content-Type": "multipart/form-data",
							},
						})
						.then((response) => {
							// // console.log(response)
							this.loading = false;
							this.$notify({
								message: "Successfully registered user",
								type: "success",
								position: "bottom-right",
							});
							this.$router.push("/apply/thank-you");
						})
						.catch((error) => {
							// console.log(error);
							// if (error === 'Error: Request failed with status code 400') {
							//  var message = "The email address you provided is already in use."
							// } else {
							//  var message = error
							// }
							this.$notify({
								message: "There was an error saving your application. Please make sure that all required fields are filled in and that your email address has not been used to register before.",
								type: "error",
								position: "bottom-right",
							});
							this.loading = false;
						});
					} else {
						this.$notify({
							message: "Please certify that the above information is true and correct",
							type: "error",
							position: "bottom-right",
						});
					}
				} else {
					this.$notify({
						message: "Missing or incorrect fields, please fill in all fields properly",
						type: "error",
						position: "bottom-right",
					});
				}
			},
			back() {
				if (this.step > 1) {
					this.step -= 1;
					if (this.step < 3) {
						this.showsubmit = false;
					}
				} else {
					this.step = 1;
				}
			},
			next() {
				if (this.step < 3) {
					this.$v[`step${this.step}`].$touch();

					if (!this.$v[`step${this.step}`].$invalid) {
						this.step += 1;

						if (this.step == 2) {
							this.checkuser();
						}

						if (this.step == 3) {
							this.showsubmit = true;
						}
					} else {
						this.$notify({
							message:
								"Missing or incorrect fields, please fill in all fields properly",
							type: "error",
							position: "bottom-right",
						});
					}
				}

				// if((this.step < 3) && (this.email) && (this.prc_num)){
				// 	this.step += 1
				// 	if(this.step === 2){
				// 		this.checkuser()
				// 	// submit email and pcr number
				// 	// get results
				// 	// show / hide relevant fields
				// 	}
				// 	if(this.step === 3){
				// 		this.showsubmit = true
				// 	}
				// }
				// else{
				// 	this.$notify({
				// 	message: 'Please provide a valid email address and PCR number.',
				// 	type: 'error',
				// 	position: 'bottom-right'
				// 	});
				// }
			},
			stepOneValidate() {},
			getRegTypes() {
				axios
					.get(this.apiDomain + "/api/registrationtype")
					.then((res) => {
						// // console.log(res.data)
						this.regTypes = res.data;
					})
					.catch((err) => {
						// console.log(err);
					});
			},
			getMemberTypes() {
				axios.get(this.apiDomain + "/api/membershiptype")
				.then((res) => {
					// // console.log(res.data);
					this.memberTypes = res.data;
				})
				.catch((err) => {
					// console.log(err);
				});
			},
			getNonMemberTypes() {
				axios
					.get(this.apiDomain + "/api/nonMembertypes")
					.then((res) => {
						// // console.log(res.data)
						this.nonmemberTypes = res.data;
					})
					.catch((err) => {
						// console.log(err);
					});
			},
			getChapterTypes() {
				axios
					.get(this.apiDomain + "/api/chaptertypes")
					.then((res) => {
						// // console.log(res.data)
						this.chapterTypes = res.data;
					})
					.catch((err) => {
						// console.log(err);
					});
			},
			getSpecialtyDivisionMembershipTypes() {
				axios.get(this.apiDomain + "/api/specialty-divisions")
				.then((res) => {
					this.specDivTypes = res.data;
				})
				.catch((err) => {
					// console.log(err);
				});
			},
			getAffiliateSocietyMembershipTypes() {
				axios.get(this.apiDomain + "/api/affiliate-societies")
				.then((res) => {
					this.affiSocTypes = res.data;
				})
				.catch((err) => {
					// console.log(err);
				});
			},
			changeValue(data) {
				// console.log(data);
			},
			changeMembershipValue(data) {
				// // console.log(data.join('-'))
				this.mem_type_id_string = data.join("-");
				// // console.log(this.mem_type_id_string);
			},
			handleAvatarSuccess(file) {
				// console.log(file);
				this.imageUrl = URL.createObjectURL(file.raw);
			},
			handlePrcIDSuccess(file) {
				// console.log(file);
				this.imageUrl3 = URL.createObjectURL(file.raw);
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
				return isPIC && isLt2M;
			},
			addAttachment(file, fileList) {
				this.attachments.push(file);
			},
			addAttachment3(file, fileList) {
				this.attachments3.push(file);
			},
			getConventionData(id) {
				axios
					.get(this.apiDomain + `api/convention/profile/forregistration/${id}`)
					.then((res) => {
						this.populate(res.data);
					})
					.catch((error) => {
						// console.log(error);
						this.fullscreenLoading = false;
					});
			},
			populate(data) {
				this.$v.step1.first_name.$model = data.first_name;
				this.$v.step1.middle_name.$model = data.middle_name;
				this.$v.step1.last_name.$model = data.last_name;
				this.$v.step1.email.$model = data.email;
				this.$v.step1.contact_num.$model = data.contact_number;
				this.step1.prc_num = data.prc_number;
				if (data.reg_type_id == 1) {
					this.trainee_status = 0;
					this.chapter_id = data.chapter_id;
					let string = data.memberships;
					string = string.split("-");
                    this.membership.mem_type_id = string.map((x) => parseInt(x));
                    // this.mem_type_id_string = data.memberships;
				} else if (data.reg_type_id == 2) {
					this.trainee_status = 1;
					this.training_ins = data.training_ins;
				} else {
				}
				// if (data.is_trainee == 1) {
				// 	this.$v.step2.reg_category.$model = "2";
				// 	this.step2.prc_num = data.prc_number;
				// 	this.$v.step2.training_inst.$model = data.training_ins;
				// } else {
				// 	this.$v.step2.reg_category.$model = "1";
				// 	this.step2.prc_num = data.prc_number;
				// 	this.chapter_id = data.chapter_id;
				// 	let string = data.memberships;
				// 	string = string.split("-");
				// 	this.mem_type_id = string.map((x) => parseInt(x));
				// }
				this.address.country = data.country;
				this.address.region = data.state;
				this.address.city = data.city;
				this.address.zip = data.zip_code;
				this.populated = true;
				this.fullscreenLoading = false;
			},
			setDOB() {
				// console.log(this.step1.birthday)
			},
			setPRCExp() {
				// console.log(this.step1.prc_expi)
			},
		},
		mounted() {
			if (this.$route.query.id) {
				this.fullscreenLoading = true;
				this.getConventionData(this.$route.query.id);
			}
			this.getRegTypes();
			this.getMemberTypes();
			this.getNonMemberTypes();
			this.getChapterTypes();
			this.getSpecialtyDivisionMembershipTypes();
			this.getAffiliateSocietyMembershipTypes();
		},
	};
</script>

<style lang="scss">
	.page-site-reg .page-header {
		padding-top: 80px;
		margin-top: 0;
		margin-bottom: 0;
	}
	.page-header .page-site-apply-header {
		background-image: url(/images/layout/mdms-reg.png);
		background-repeat: no-repeat;
		background-size: contain;
		width: 100%;
		height: 0;
		padding-top: 26.0417%;
		position: relative;
	}
	.page-site-apply-header-text {
		position: absolute;
		width: 60%;
		bottom: 0;
		top: 42%;
		left:40%;
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
	.btn-submit {
		width: 40%;
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
	.sec-membership-info {	
	    margin-top: 12px;
	}
	@media only screen and (max-width: 1220px) {
		.page-header .page-site-apply-header {
			background-image: url(/images/layout/mdms-reg.png);
			background-repeat: no-repeat;
			background-size: cover;
			height: 150px;
			padding-top: 26.0417%;
			position: relative;
		}
		.page-site-apply-header-text {
			top: 30%;
		}
	}
	@media only screen and (max-width: 768px) {
		.page-site-apply-header-text {
			width:40%;
			top: 30%;
			left:70%;
			font-size: 1rem;
			color: #fff;
			font-weight: bold;
		}
	}
	/* @media only screen and (min-width: 960px) {
		.registration-card {
			width: 35%;
		}
	} */

.name-uppercase input {
	text-transform :uppercase!important;
}

#apply-page {
	.note {
		font-size: 12px;
	}
}
</style>