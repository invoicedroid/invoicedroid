<template>
    <div>
        <el-steps :active="1" align-center>
            <el-step></el-step>
            <el-step></el-step>
            <el-step></el-step>
        </el-steps>
        <div class="id-margin id-margin-medium-top">
            <el-card class="box-card" shadow="always">
                <div slot="header" class="clearfix">
                    <span>{{ $t('install.steps.language')}}</span>
                </div>
                <el-form ref="form" :rules="rules" :model="form" label-width="0" method="POST" action="/install/language">
                    <input type="hidden" name="_token" :value="token"/>
                    <input type="hidden" name="language" :value="form.language"/>
                    <el-form-item  prop="language">
                        <el-select clearable filterable v-model="form.language" placeholder="Select" class="id-width-1-1">
                            <el-option
                                v-for="(item, key, index) in options"
                                :key="key"
                                :label="item"
                                :value="key">
                            </el-option>
                        </el-select>
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
        name: "LanguageSelection",
        data() {
            return {
                options: languages,
                token: document.head.querySelector('meta[name="csrf-token"]').content,
                value: '',
                form: {
                    language: ''
                },
                rules: {
                    language: [
                        { required: true, message: this.$t('install.language.select'), trigger: 'change' }
                    ],
                }
            }
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.$refs.form.$el.submit()
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        }
    }
</script>
