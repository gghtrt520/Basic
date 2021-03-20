<template>
  <div>
    <div class="container" style="border: none;">
      <el-row style="margin-bottom: 10px;">
        <el-col :span="3">
          <el-button type="success" icon="el-icon-circle-plus" @click="handleEidt(0)">添加文章</el-button>
        </el-col>
      </el-row>
      <el-table :data="list" border v-loading="ifload">
        <el-table-column prop="title" label="标题" align="center" width="300">
        </el-table-column>
        <el-table-column prop="resume" align="center" label="描述" width="300">
        </el-table-column>
        <el-table-column prop="image" align="center" label="Logo图">
          <template slot-scope="scope">
            <img :src="scope.row.image" width="40%" />
          </template>
        </el-table-column>
        <el-table-column v-if="roleId == 1" prop="is_available" align="center" label="审核" width="80">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.is_available" @change="availableChange(scope.row.id,scope.row.is_available)" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
          </template>
        </el-table-column>
        <el-table-column align="center" label="操作" width="150">
          <template slot-scope="scope">
            <el-button type="text" icon="el-icon-document" @click="handleEidt(scope.row.id)">
              修改
            </el-button>
            <el-button type="text" icon="el-icon-delete" class="red" @click="handleDel(scope.$index, scope.row)">
              删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="pagination">
        <el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="pages" :page-size="page_size">
        </el-pagination>
      </div>

    </div>

    <!-- 删除提示框 -->
    <el-dialog title="删除提示" :visible.sync="delVisible" width="300px" center>
      <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="delVisible = false">取 消</el-button>
        <el-button :loading="ifload" type="primary" @click="delData">确 定</el-button>
      </span>
    </el-dialog>

  </div>
</template>

<script type="text/javascript">
import { download } from '@/components/js/request'
import upload from '@/components/utils/upload';
export default {
  components: { upload },
  data() {
    return {
      roleId: 0,
      ifload: true,
      page: 1,
      page_size: 10,
      pages: 0,
      list: [],

      //当前操作对象
      curId: 0,
      curIndex: -1,
      delVisible: false,
      editVisible: false,


    }
  },
  created() {
    this.$post_('admin/user/user_info', {}, (res) => {
      this.roleId = res.data.role_id;
    });
    this.$nextTick(() => {
      this.getData();
    })
  },

  methods: {
    getData() {
      this.ifload = true;
      this.$post_('content/content/list', { page: this.page }, (res) => {
        if (res.code == '0') {
          this.list = res.data.map(item => {
            item.is_available = item.is_available == 1 ? true : false
            return item
          });;
          this.pages = Number(res.extend.pages);
          this.ifload = false;
        }
      });
    },
    // 分页导航
    handleCurrentChange(val) {
      this.page = val;
      this.getData();
    },

    //修改
    handleEidt(id) {
      this.$router.push({ path: '/page/platform/article_edit', query: { id: id } })
    },

    availableChange(id, value) {
      this.$post_('content/content/update', { id: id, is_available: (value ? 1 : 0) }, (res) => {
        if (res.code == '0') {
          this.$message.success(res.msg);
        }
      });
    },
    //删除确认
    handleDel(index, row) {
      this.delVisible = true;
      this.curId = row.id;
      this.curIndex = index;
    },
    showImg(url) {
      this.form.img_url = url;
    },
    //删除
    delData() {
      this.ifload = true;
      let param = { id: this.curId };
      this.$post_('content/content/delete', param, (res) => {
        console.log(res);
        if (res.code == '0') {
          this.$message.success(res.msg);
          this.list.splice(this.curIndex, 1);
          this.ifload = false;
          this.delVisible = false;
        } else {
          this.$message.error(res.msg);
        }
      })
    },
  }
}
</script>

<style type="text/css">
thead tr th {
  text-align: center;
}
.red {
  color: #ff0000;
}
.search {
  margin-bottom: 10px;
}
</style>
