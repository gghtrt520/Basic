<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-lx-calendar"></i>轮播管理</el-breadcrumb-item>
        <el-breadcrumb-item><i class="el-icon-lx-calendar"></i>轮播列表</el-breadcrumb-item>
        <el-breadcrumb-item>轮播编辑</el-breadcrumb-item>
      </el-breadcrumb>
    </div>

    <div class="container">
      <el-tabs v-model="activeName" type="card">
        <!-- <el-tab-pane label="基本信息" name="base">
          <goodsBase v-if="activeName=='base'" :id="id" @baseSuccess="baseSuccess"></goodsBase>
        </el-tab-pane>
        <el-tab-pane label="产品详情" name="detail" v-if="id>0">
          <goodsDetail v-if="activeName=='detail'" :id="id"></goodsDetail>
        </el-tab-pane> -->
        <el-tab-pane label="详情" name="images">
          <goodsImages :id="id"></goodsImages>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script type="text/javascript">
import goodsBase from '@/components/goods/goods_base';
import goodsDetail from '@/components/goods/goods_detail';
import goodsImages from '@/components/goods/goods_images';
export default {
  components: { goodsBase, goodsDetail, goodsImages },
  data() {
    return {
      activeName: 'images',
      id: 1,
    }
  },
  created() {
    // this.id = Number(this.$route.query.goods_commonid);
  },
  methods: {
    //添加成功
    baseSuccess(id) {
      this.id = Number(id);
      this.activeName = 'detail';
    }
  }
}
</script>

<style>
</style>
