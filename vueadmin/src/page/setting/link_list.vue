<template>
  <div class="table">
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 网站设置</el-breadcrumb-item>
        <el-breadcrumb-item>友情链接分类</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="container">
      <div>
        <el-button icon="el-icon-circle-plus" @click="handleAdd" type="success">添加</el-button>
      </div>
      <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

        <el-table-column prop="id" align="center" label="序号" sortable width="150"></el-table-column>
        <el-table-column prop="category" align="center" label="分类名称"> </el-table-column>
        <el-table-column label="操作" width="240" align="center">
          <template slot-scope="scope">
            <el-button type="text" icon="el-icon-edit" @click="handleDetail(scope.$index)">详情</el-button>
            <!-- <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button> -->
            <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="pagination">
        <el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="total" :page-size="page_size">
        </el-pagination>
      </div>
    </div>

    <!-- 编辑弹出框 -->
    <el-dialog :title="idx>0?'编辑':'添加'" :visible.sync="editVisible" width="30%">
      <el-form ref="form" :model="form" label-width="100px">
        <el-form-item label="分类名称">
          <el-input v-model="form.category"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="editVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveEdit">确 定</el-button>
      </span>
    </el-dialog>

    <!-- 删除提示框 -->
    <el-dialog title="提示" :visible.sync="delVisible" width="300px" center>
      <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="delVisible = false">取 消</el-button>
        <el-button type="primary" @click="deleteRow">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tableData: [],
      page: 1,
      page_size: 10,
      total: 0,
      multipleSelection: [],
      editVisible: false,
      delVisible: false,
      form: {
        category: '',
        id: 0,
      },
      idx: -1,
      id: 0,
      roleList: [],
    }
  },
  created() {
    console.log('created');
    this.getData();
  },

  methods: {
    // 分页导航
    handleCurrentChange(val) {
      this.page = val;
      this.getData();
    },
    // 友情链接分类列表
    getData() {
      let params = { page: this.page, page_size: this.page_size };
      this.$post_('setting/link-category/list', params, (res) => {
        console.log(res);
        this.tableData = res.data;
        this.roleList = res.extend.role_list;
        this.total = Number(res.extend.pages);
        this.tableData.name = res.data.lebel;
      });
    },
    formatRole(row, column) {
      // console.log(row);
      return this.roleList[row.role_id];
    },
    //添加
    handleAdd() {
      this.form.category = '';
      this.form.id = 0;
      this.idx = -1;
      this.id = 0;
      this.editVisible = true;
    },

    //修改
    handleEdit(index, row) {
      this.idx = index;
      this.id = row.id;
      const item = this.tableData[index];
      this.form = {
        category: item.category,
        id: this.id,
      }
      this.editVisible = true;
    },
    //详情
    handleDetail(index) {
      this.$router.push({
        path: "/page/setting/link_list_edit",
        query: { id: this.tableData[index].id },
      });
    },
    handleDelete(index, row) {
      this.id = row.id;
      this.idx = index;
      this.delVisible = true;
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },

    // 保存编辑
    saveEdit() {
      // console.log(this.form);return;
      this.$post_('setting/link-category/add', this.form, (res) => {
        console.log(res);
        if (res.code == '0') {
          if (this.id < 1) {
            // this.tableData.push(res.data);
            this.getData();
          }
          if (this.id > 0) this.$set(this.tableData, this.idx, res.data);
          this.$message.success(res.msg);
        } else {
          this.$message.success(res.msg);
        }
      })
      this.editVisible = false;
    },
    // 确定删除
    deleteRow() {
      this.$post_('setting/link-category/delete', { id: this.id }, (res) => {
        console.log(res);
        if (res.code == '0') {
          this.$message.success(res.msg);
        } else {
          this.$message.warning(res.msg);
        }
      })
      this.delVisible = false;
      this.tableData.splice(this.idx, 1);
    },
  }
}

</script>

<style scoped>
.iconfont {
  font-size: 20px;
  /*font-weight: bold;*/
}
.handle-box {
  margin-bottom: 20px;
}

.handle-select {
  width: 120px;
}

.handle-input {
  width: 300px;
  display: inline-block;
}
.del-dialog-cnt {
  font-size: 16px;
  text-align: center;
}
.table {
  width: 100%;
  font-size: 14px;
}
.red {
  color: #ff0000;
}
</style>
