<template>
  <div></div>
</template>

<script>
export default {
  data() {
    return {};
  },
  created() {
    this.logout();
  },
  methods: {
    logout(recursive = false) {
      if (!recursive) {
        events.$emit("user:logout");
      }
      //this.$router.push("/login");
    }
  }
};
</script>
