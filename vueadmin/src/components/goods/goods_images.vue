<template>
  <div v-loading="ifload">
    <el-row>
      <el-col :xs="24" :sm="20" :md="20" :lg="20" :xl="20">
        <el-form ref="form">
          <el-row>
            <el-col>
              <div class="upload">
                <el-upload class="upload-demo" :action="baseUrl +'setting/banner-list/upload'" :on-remove="remove" :on-success="save" :file-list="fileList" list-type="picture-card">
                  <i class="el-icon-plus"></i>
                </el-upload>
              </div>
            </el-col>
          </el-row>
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
      fileList: [],
      // fileList: [{ url: 'http://localhost//upload/banner/file/1614701612.0728.jpg' }],
    }
  },
  created() {
    this.$post_('setting/banner-list/list', { loop_banner_id: this.$route.query.id }, (res) => {
      if (res.code == '0') {
        this.fileList = res.data;
      } else {
        this.$message.error(res.msg);
      }
    })
  },
  methods: {
    remove(file) {
      let that = this;
      this.fileList.forEach((item, index) => {
        if (item.id == file.id) {
          this.$post_('setting/banner-list/delete', { id: file.id }, (res) => {
            if (res.code == '0') {
              that.fileList.splice(index, 1)
              that.$message.success('删除成功！');
              that.$post_('setting/banner-list/list', { loop_banner_id: that.$route.query.id }, (res) => {
                if (res.code == '0') {
                  that.fileList = res.data
                }
              })
            } else {
              that.$message.error(res.msg);
            }
          })
        }
      })
    },
    save(response, file, fileList) {
      let param = {
        loop_banner_id: this.$route.query.id,
        url: file.response.data
      }
      this.add(param)
    },
    add(param) {
      let that = this;
      this.$post_('setting/banner-list/add', param, (res) => {
        this.ifload = false;
        if (res.code == '0') {
          that.fileList.push(res.data)
          this.$message.success(res.msg);

        } else {
          this.$message.error(res.msg);
        }
      })
    }
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
