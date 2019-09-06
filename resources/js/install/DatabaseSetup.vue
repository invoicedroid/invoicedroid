<template>
    <div>
        <el-steps :active="2" align-center>
            <el-step></el-step>
            <el-step></el-step>
            <el-step></el-step>
        </el-steps>
        <div class="id-margin id-margin-medium-top">
            <el-card class="box-card" shadow="always">
                <div slot="header" class="clearfix">
                    <span>{{ $t('install.steps.database')}}</span>
                </div>
                <el-form autocomplete="off" ref="form" :rules="rules" :model="form" label-width="0" v-loading="loading">
                    <input type="hidden" name="_token" :value="token"/>
                    <el-form-item prop="hostname">
                        <el-input
                            :placeholder="$t('install.database.hostname')"
                            prefix-icon="el-icon-files"
                            clearable
                            name="hostname"
                            v-model="form.hostname">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="port">
                        <el-input
                            :placeholder="$t('install.database.port')"
                            prefix-icon="el-icon-lock"
                            clearable
                            name="port"
                            v-model="form.port">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="username">
                        <el-input
                            :placeholder="$t('install.database.username')"
                            prefix-icon="el-icon-user"
                            clearable
                            name="username"
                            v-model="form.username">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="password">
                        <el-input
                            :placeholder="$t('install.database.password')"
                            prefix-icon="el-icon-key"
                            show-password
                            autocomplete="new-password"
                            name="password"
                            v-model="form.password">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="name">
                        <el-input
                            :placeholder="$t('install.database.name')"
                            prefix-icon="el-icon-coin"
                            clearable
                            name="name"
                            v-model="form.name">
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
        name: "DatabaseSetup",
        data() {
            return {
                token: document.head.querySelector('meta[name="csrf-token"]').content,
                loading: false,
                form: {
                    hostname: 'localhost',
                    username: '',
                    password: '',
                    name: '',
                    port: '3306'
                },
                rules: {
                    username: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    hostname: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    port: [
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
                        axios.post('/install/database', this.form)
                            .then(() => {
                                // everything is ok
                                window.location.href = '/install/settings'
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
