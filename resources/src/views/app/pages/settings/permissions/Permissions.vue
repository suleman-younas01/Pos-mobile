<template>
  <div class="main-content">
    <breadcumb :page="$t('GroupPermissions')" :folder="$t('User_Management')" />

    <b-card>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $t('GroupPermissions') }}</h4>
        <b-button variant="primary" size="sm" @click="loadRoles">
          <lucide-icon name="refresh-cw" class="mr-1" /> {{ $t('Refresh') || 'Refresh' }}
        </b-button>
      </div>

      <b-table
        :items="roles"
        :fields="fields"
        :busy="loading"
        responsive
        striped
        hover
        show-empty
      >
        <template #cell(permissions)="{ item }">
          <b-badge
            v-for="permission in item.permissions"
            :key="permission"
            variant="light"
            class="mr-1 mb-1"
          >
            {{ permission }}
          </b-badge>
        </template>
      </b-table>
    </b-card>
  </div>
</template>

<script>
export default {
  metaInfo: {
    title: "Group Permissions"
  },
  data() {
    return {
      loading: false,
      roles: [],
      fields: [
        { key: "id", label: "#", sortable: true },
        { key: "name", label: this.$t("Role") || "Role", sortable: true },
        { key: "permissions", label: this.$t("Permissions") || "Permissions" }
      ]
    };
  },
  methods: {
    async loadRoles() {
      this.loading = true;
      try {
        const response = await axios.get("roles?page=1&limit=100");
        this.roles = response.data.roles || [];
      } finally {
        this.loading = false;
      }
    }
  },
  created() {
    this.loadRoles();
  }
};
</script>
