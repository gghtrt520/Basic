<template>
  <div class="table">
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-lx-cascades"></i>网站设置</el-breadcrumb-item>
        <el-breadcrumb-item>子菜单设置</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="container">
      <div>
        <el-button @click="handleAdd" type="success">添加</el-button>
        <el-button class="reback" type="success">
          <router-link to="/page/setting/menu_list">返回</router-link>
        </el-button>
      </div>
      <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

        <!-- <el-table-column type="selection" width="55" align="center"></el-table-column> -->
        <el-table-column prop="id" label="序号" sortable width="100" align="center"></el-table-column>
        <el-table-column prop="name" label="名称" width="160" :formatter="formatname"> </el-table-column>
        <el-table-column prop="path" label="路由"></el-table-column>
        <!-- <el-table-column prop="is_menu" label="是菜单"></el-table-column> -->
        <el-table-column prop="sort" label="排序"></el-table-column>
        <!-- <el-table-column prop="icon" label="图标">
          <template slot-scope="scope">
            <i :class="scope.row.icon"></i>
          </template>
        </el-table-column> -->

        <el-table-column label="操作" width="240" align="center">
          <template slot-scope="scope">
            <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <!-- <div class="pagination">
        <el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :total="total" :page-size="page_size">
        </el-pagination>
      </div> -->
    </div>

    <!-- 编辑弹出框 -->
    <el-dialog :title="idx>0?'编辑':'添加'" :visible.sync="editVisible" width="30%">
      <el-form ref="form" :model="form" label-width="100px">
        <el-form-item label="名称">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item label="sort">
          <el-input v-model="form.sort"></el-input>
        </el-form-item>
        <el-form-item label="路由">
          <el-input v-model="form.path"></el-input>
        </el-form-item>
        <!-- <el-form-item label="图标">
          <i :class="form.icon"></i>
          <span @click="showIcon=true">选择图标</span>
        </el-form-item> -->
        <!-- <el-form-item label="描述">
          <el-input v-model="form.desc"></el-input>
        </el-form-item> -->
        <!-- <el-form-item label="是否菜单">
          <el-radio v-model="form.is_menu" label="1">是</el-radio>
          <el-radio v-model="form.is_menu" label="0">否</el-radio>
        </el-form-item> -->
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
      url: './static/vuetable.json',
      tableData: [],
      page: 1,
      page_size: 10,
      total: 0,
      multipleSelection: [],
      editVisible: false,
      delVisible: false,
      showIcon: false,
      form: {
        name: '',
        desc: '',
        path: '',
        is_menu: '1',
        id: 0,
        icon: '',
      },
      idx: -1,
      id: 0,
      parent_id: 0,
      level: -1,
    }
  },
  created() {
    this.getData();
  },

  methods: {
    // 分页导航
    handleCurrentChange(val) {
      this.page = val;
      this.getData();
    },
    getData() {
      let params = { page: this.page, page_size: this.page_size };
      this.$post_('setting/menu/list', params, (res) => {
        if (res.code == "0") {
          this.parent_id = res.data[this.$route.query.index].id;
          this.tableData = res.data[this.$route.query.index].children.map(item => {
            item.name = item.label;
            return item
          });
          this.total = 0;
          this.tableData.name = res.data.lebel;
        }
      });
    },
    formatname(row,) {
      return  row.name;
    },
    formaticon(row, column) {
      return '<i class="' + row.icon + '"></i>';
    },
    //添加
    handleAdd() {
      this.form.name = '';
      this.form.path = '';
      this.form.desc = '';
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
        name: item.name,
        path: item.path,
        desc: item.desc,
        is_menu: item.is_menu,
        sort: item.sort,
        id: this.id,
        icon: item.icon,
      }
      this.editVisible = true;
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
      this.form.parent_id = this.parent_id;
      this.$post_('setting/menu/' + (this.id == 0 ? 'add' : 'update'), this.form, (res) => {
        if (res.code == '0') {
          this.getData();
          this.$message.success(res.msg);
        } else {
          this.$message.success(res.msg);
        }
      })
      this.editVisible = false;
    },
    // 确定删除
    deleteRow() {
      this.$post_('setting/menu/delete', { id: this.id, parent_id: this.parent_id }, (res) => {
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
    //添加子菜单
    addSub(index, row) {
      window.localStorage.menuData = JSON.stringify(row);
      this.$router.push({
        path: '/page/setting/menu_list_children',
      })
    },

    selectIcon(icon) {
      console.log(icon);
      this.showIcon = false;
      this.form.icon = icon;
    }
  }
}

</script>

<style scoped>
.reback {
  padding: 0;
}
.reback span a {
  padding: 9px 15px;
  color: #fff;
  display: inline-block;
  height: 100%;
}
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
