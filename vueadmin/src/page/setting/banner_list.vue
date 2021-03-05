<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-lx-calendar"></i>网站设置</el-breadcrumb-item>
        <el-breadcrumb-item>轮播图</el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <div class="container">
      <div class="op">
        <el-button type="primary" @click="addDialog = true">添加轮播图分类</el-button>
        <el-dialog title="添加轮播图分类" :visible.sync="addDialog" width="30%">
          <el-form ref="form" :model="form" label-width="50px">
            <el-form-item label="描述">
              <el-input v-model="form.discribe"></el-input>
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button @click="addDialog = false">取 消</el-button>
            <el-button type="primary" @click="addBannerClassify">确 定</el-button>
          </span>
        </el-dialog>
      </div>
      <el-table :data="list" border v-loading="ifload" style="width: 100%">
        <el-table-column prop="id" label="序号" width="300"> </el-table-column>
        <el-table-column prop="title" label="描述"> </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button type="text" icon="el-icon-edit" @click="eidtClassify(scope.row)">
              编辑
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
        <el-button :loading="ifload" type="primary" @click="delClassify">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script type="text/javascript">
export default {
  data() {
    return {
      ifload: true,
      list: [],
      form: {
        discribe: "",
      },
      page: 1,
      page_size: 10,
      pages: 0,

      addDialog: false,
      delCommonClassifyId: 0,
      delIndex: -1,
      delVisible: false,
    };
  },
  created() {
    this.$nextTick(() => {
      this.getData();
    });
  },
  computed: {},
  methods: {
    getData() {
      let params = { page: this.page, page_size: this.page_size };
      this.$post_("setting/banner/list", params, (res) => {
        console.log(res);
        if (res.code == "0") {
          this.list = res.data;
          this.pages = Number(res.extend.pages);
          console.log(this.pages);
          this.ifload = false;
        }
      });
    },
    // 分页导航
    handleCurrentChange(val) {
      this.page = val;
      this.getData();
    },
    addBannerClassify() {
      this.$post_("setting/banner/add", { title: this.form.discribe }, (res) => {
        if (res.code == "0") {
          this.getData();
          this.$message.success("添加成功！");
          this.addDialog = false;
        } else {
          this.$message.warning(res.msg);
        }
      });
    },
    eidtClassify(classify) {
      this.$router.push({
        path: "/page/setting/goods_edit",
        query: { id: classify.id },
      });
    },
    handleDel(index, classify) {
      this.delVisible = true;
      this.delCommonClassifyId = classify.id;
      this.delIndex = index;
    },
    delClassify() {
      this.ifload = true;
      let param = { id: this.delCommonClassifyId };
      this.$post_("setting/banner/delete", param, (res) => {
        if (res.code == "0") {
          this.$message.success(res.msg);
          this.list.splice(this.delIndex, 1);
        } else {
          this.$message.error(res.msg);
        }
        this.ifload = false;
        this.delVisible = false;
      });
    },
  },
};
</script>

<style type="text/css">
.red {
  color: #ff0000;
}
</style>
