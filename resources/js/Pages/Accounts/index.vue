<template>
  <AuthenticatedLayout :translations="translations">
    <!-- breadcrumb-->
    <div class="pagetitle">
      <h1>{{ translations.accounts }}</h1>
      <nav>
        <ol class="breadcrumb">
  
          <li class="breadcrumb-item active">{{ translations.accounts }}</li>

          <li class="breadcrumb-item">
            <Link class="nav-link d-inline" :href="route('dashboard')">
              {{ translations.Home }}
            </Link>
          </li>
        </ol>
      </nav>
    </div>
    <!-- End breadcrumb-->

    <section class="section dashboard">
      <div class="card">
        <div class="card-header">
          <div class="d-flex">
          </div>
        </div>
        <div class="card-body">
          <form @submit.prevent="Filter">
            <div class="row filter_form">
              <div class="col-md-3">
                <input type="text" class="form-control search_box" v-model="filterForm.name" 
                  :placeholder="translations.name" />
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control search_box" v-model="filterForm.model" 
                  :placeholder="translations.model" />
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                  {{ translations.search }} &nbsp; <i class="bi bi-search"></i>
                </button>
              </div>
              <div class="col-md-3">
                <Link v-if="hasPermission('create account')" class="btn btn-primary" :href="route('accounts.create')">
                  {{ translations.create }} &nbsp; <i class="bi bi-plus-circle"></i>
                </Link>
              </div>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table text-center">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">{{ translations.name }}</th>
                  <th scope="col">{{ translations.model }}</th>
                  <th scope="col">{{ translations.quantity }}</th>
                  <th scope="col">{{ translations.selling_price }}</th>
                  <th scope="col">{{ translations.created_at }}</th>
                  <th scope="col"> {{ translations.status }}</th>
                  <th scope="col" v-if="hasPermission('update accounts')">{{ translations.edit }}</th>
                  <th scope="col" v-if="hasPermission('delete accounts')">{{ translations.delete }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(account, index) in accounts?.data" :key="account.id">
                  <th scope="row">{{ index + 1 }}</th>
                  <td>{{ account.name }}</td>
                  <td>{{ account.model }}</td>
                  <td>{{ account.quantity }}</td>
                  <td>{{ account.selling_price }}</td>
                  <td>{{ account.created }}</td>
                  <td>
                  <div>
                    <label class="inline-flex items-center me-5 cursor-pointer">
                      <input type="checkbox" class="sr-only peer" :checked="account.is_active == 1"
                        @change="Activate(account.id)">
                      <div
                        class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                      </div>
                    </label>
                  </div>
                  </td>
                  <td v-if="hasPermission('update accounts')">
                    <a class="btn btn-primary" :href="route('accounts.edit', { account: account.id })">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                  </td>
                  <td v-if="hasPermission('delete accounts')">
                    <button type="button" class="btn btn-danger" @click="Delete(account.id)">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <Pagination :links="accounts?.links" />
    </section>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
  accounts: Object, 
  translations: Array 
});

const page = usePage();

const filterForm = reactive({
  name: '',
  model: ''
});

const Filter = () => {
  router.get(
    route('accounts.index'),
    filterForm,
    { preserveState: true, preserveScroll: true },
  );
};

const hasPermission = (permission) => {
  return page.props.auth_permissions.includes(permission);
};
const Activate = (id) => {
  Swal.fire({
    title: props.translations.are_your_sure,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#7066e0',
    confirmButtonText: props.translations.yes,
    cancelButtonText: props.translations.cancel,
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(`/accounts/${id}/activate`, {
        onSuccess: () => {
          Swal.fire(
            'Updated !',
            'account stuaus item has been updated.',
            'success'
          );
        },
        onError: () => {
          Swal.fire(
            'Error!',
            'There was an issue updating account status.',
            'error'
          );
        }
      });
    }
  });
}
const Delete = (id) => {
  Swal.fire({
    title: props.translations.are_you_sure,
    text: props.translations.you_will_not_be_able_to_revert_this,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: props.translations.yes,
    cancelButtonText: props.translations.cancel,
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete('accounts/' + id, {
        onSuccess: () => {
          Swal.fire({
            title: props.translations.data_deleted_successfully,
            icon: "success"
          });
        },
        onError: () => {
          Swal.fire(
            'Error!',
            'There was an issue deleting the account.',
            'error'
          );
        }
      });
    }
  });
};
</script>
