<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-lx-calendar"></i>平台管理</el-breadcrumb-item>
        <el-breadcrumb-item>
          <router-link to="/page/platform/article"> <i class="el-icon-lx-calendar"></i>文章列表</router-link>
        </el-breadcrumb-item>
        <el-breadcrumb-item>文章编辑</el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <div class="container">
      <el-page-header @back="$router.back(-1)" content="文章编辑" style="margin-bottom: 20px;">
      </el-page-header>
      <el-row>
        <el-col :xs="24" :sm="20" :md="20" :lg="20" :xl="20">
          <el-form ref="form" :rules="rules" :model="form" label-width="100px">
            <el-form-item label="文章标题" prop="title">
              <el-input v-model="form.title"></el-input>
            </el-form-item>
            <el-form-item label="文章简述" prop="resume">
              <el-input v-model="form.resume"></el-input>
            </el-form-item>
            <el-form-item label="文章分类">
              <el-cascader v-model="form.menu_id" :options="articleClass" :props="{value: 'id', label: 'label', checkStrictly: true }"></el-cascader>
            </el-form-item>
            <el-form-item label="文章Logo">
              <img :src="form.image" v-show="form.image" style="max-width: 150px;" />
              <upload :uploadType="'2'" :size="32" @showImg="showImg"></upload>
            </el-form-item>
            <el-form-item label="文章内容">
              <editor v-if="initFlag" @getContent="getContent" :initContent="form.content"></editor>
            </el-form-item>

            <el-form-item label="">
              <el-button :loading="ifload" type="primary" @click="saveData">保存</el-button>
            </el-form-item>

          </el-form>
        </el-col>
      </el-row>

    </div>

    <loading :ifload="ifload"></loading>
  </div>
</template>

<script type="text/javascript">
import loading from '@/components/utils/loading';
import editor from '@/components/utils/editor';
import upload from '@/components/utils/upload';
export default {
  components: { loading, editor, upload },
  data() {
    return {
      ifload: false,
      initFlag: false, //用于富文本编辑器
      form: {
        title: '',
        resume: '',
        image: '',
        content: '',
        menu_id: '',
      },
      rules: {
        resume: [
          { required: true, message: '请输入文章简述', trigger: 'blur' },
        ],
        title: [
          { required: true, message: '请输入文章标题', trigger: 'blur' },
        ],
      },
      articleId: 0,
      articleClass: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
    }
  },
  created() {
    this.articleId = this.$route.query.id;
    this.$nextTick(() => {
      this.getData();
    })
  },
  methods: {
    getData() {
      this.ifload = true;
      this.$post_('setting/menu/list', {}, (res) => {
        this.articleClass = res.data;
        if (this.articleId < 1) {
          this.ifload = false;
        } else {
          this.$post_('content/content/list', { id: this.articleId }, (res) => {
            if (res.code == '0') {
              console.log(res);
              this.form.title = res.data[0].title;
              this.form.resume = res.data[0].resume;
              this.form.menu_id = res.data[0].menu_id;
              this.form.image = res.data[0].image;
              this.form.content = res.data[0].content;
              this.ifload = false;
            } else {
              this.$message.error(res.msg);
            }
            this.initFlag = true;
          })
        }

      })
    },

    //获取编辑器内容
    getContent(content) {
      this.form.content = content;
    },
    //上传的图片
    showImg(imgUrl) {
      this.form.image = imgUrl;
    },
    //保存数据
    saveData() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.form.id = this.articleId;
          let data = this.form;
          data.menu_id = data.menu_id[0];
          this.$post_('content/content/' + (this.articleId ? 'update' : 'add'), data, (res) => {
            if (res.code == '0') {
              this.$message.success(res.msg);
              this.ifload = false;
              this.$router.push('/page/platform/article_list');
            } else {
              this.$message.error(res.msg);
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    }
  }
}
</script>

<style scoped="scoped">
</style>
