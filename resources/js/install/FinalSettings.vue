<template>
    <div>
        <el-steps :active="3" align-center>
            <el-step></el-step>
            <el-step></el-step>
            <el-step></el-step>
        </el-steps>
        <div class="id-margin id-margin-medium-top">
            <el-card class="box-card" shadow="always">
                <div slot="header" class="clearfix">
                    <span>{{ $t('install.steps.settings')}}</span>
                </div>
                <el-form autocomplete="off" ref="form" :rules="rules" :model="form" label-width="0" v-loading="loading">
                    <input type="hidden" name="_token" :value="token"/>
                    <el-form-item prop="company_name">
                        <el-input
                            :placeholder="$t('install.settings.company_name')"
                            clearable
                            name="company_name"
                            v-model="form.company_name">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="company_email">
                        <el-input
                            :placeholder="$t('install.settings.company_email')"
                            clearable
                            name="company_email"
                            v-model="form.company_email">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="admin_name">
                        <el-input
                            :placeholder="$t('install.settings.admin_name')"
                            clearable
                            name="admin_email"
                            v-model="form.admin_name">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="admin_email">
                        <el-input
                            :placeholder="$t('install.settings.admin_email')"
                            clearable
                            name="admin_email"
                            v-model="form.admin_email">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="admin_password">
                        <el-input
                            :placeholder="$t('install.settings.admin_password')"
                            show-password
                            autocomplete="new-password"
                            name="admin_password"
                            v-model="form.admin_password">
                        </el-input>
                    </el-form-item>
                    <div class="id-text-right">
                        <el-button type="primary" @click="submitForm('form')">{{ $t('install.next') }}</el-button>
                    </div>
                </el-form>
            </el-card>
        </div>

    </div>
</template>

<script>
    export default {
        name: "FinalSettings",
        data() {
            return {
                token: document.head.querySelector('meta[name="csrf-token"]').content,
                loading: false,
                form: {
                    company_name: '',
                    company_email: '',
                    admin_name: '',
                    admin_email: '',
                    admin_password: ''
                },
                rules: {
                    company_name: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    company_email: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    admin_name: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    admin_email: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    admin_password: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                }
            }
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.loading = true
                        axios.post('/install/settings', this.form)
                            .then(() => {
                                // everything is ok
                                window.location.href = '/'
                            })
                            .catch((error) => {
                                this.$message.error(error.response.data.message)
                            })
                            .finally(() => {
                                this.loading = false
                            })
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        }
    }
</script>
