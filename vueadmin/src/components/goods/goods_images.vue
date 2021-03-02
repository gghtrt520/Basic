<template>
  <div v-loading="ifload">
    <el-row>
      <el-col :xs="24" :sm="20" :md="20" :lg="20" :xl="20">
        <el-form ref="form" :model="form">

          <el-row>
            <el-form-item :label="item.name?item.name:'默认'" v-for="(item,index) in data" :key="index">
              <el-col class="col" :span="4" v-for="(images,i) in item.images" :key="i">
                <div class="image">
                  <img :src="images.image_url" width="60%" />
                  <i class="el-icon-delete" @click="delImg(index,i)"></i>
                </div>
                <div class="extra">
                  <el-input placeholder="排序" size="mini" class="sort" v-model="data[index]['images'][i]['sort']">
                    <i slot="prefix" class="el-input__icon el-icon-sort"></i>
                  </el-input>
                </div>
              </el-col>
            </el-form-item>
            <el-col>
              <div class="upload">
                <el-upload class="upload-demo" :action="baseUrl +'setting/banner-list/upload'" :on-remove="remove" :on-success="save" :file-list="fileList" list-type="picture-card">
                  <i class="el-icon-plus"></i>
                </el-upload>
              </div>
            </el-col>
          </el-row>

          <el-form-item label="">
            <el-button :loading="ifload" type="primary" @click="save">保存</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <loading :ifload="ifload"></loading>
  </div>
</template>

<script type="text/javascript">
import { baseUrl } from '@/components/js/request.js';
import loading from '@/components/utils/loading';
export default {
  components: { loading },
  props: {
    id: { type: Number, default: 0 },
  },
  data() {
    return {
      baseUrl: baseUrl,
      ifload: false,
      form: {},
      fileList: [],
    }
  },
  created() {
    this.form.goods_commonid = this.id;
  },
  methods: {

    remove() {

    },
    save(response, file, fileList) {
      // debugger
      console.log(file);
      let param = {
        id: this.$route.query.id,
        path: file.response.data
      }
      this.$post_('setting/banner-list/add', param, (res) => {
        console.log(res);
        this.ifload = false;
        if (res.code == '0') {
          this.$message.success(res.msg);
        } else {
          this.$message.error(res.msg);
        }
      })
    },
  },
}
</script>

<style scoped="scoped">
.col {
  /* background: yellow; */
  height: 150px;
}
.image {
  position: relative;
  height: 100px;
  /* background: red; */
  overflow: hidden;
  text-align: center;
  padding: 5px 10px;
}
.image img {
  width: 100%;
  border: 1px solid #f0f0f0;
}
.image .el-icon-delete {
  position: absolute;
  right: 3px;
  top: 0px;
  color: red;
}
.extra {
  text-align: center;
}
.extra .sort {
  width: 60%;
}
</style>
