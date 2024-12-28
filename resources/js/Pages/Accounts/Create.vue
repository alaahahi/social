<template>
  <AuthenticatedLayout :translations="translations">
    <!-- Breadcrumb -->
    <div class="pagetitle">
      <h1>{{ translations.accounts }}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">{{ translations.create }}</li>

          <li class="breadcrumb-item ">
            <Link class="nav-link d-inline" :href="route('accounts.index')">
              {{ translations.accounts }}
            </Link>
          </li>

          <li class="breadcrumb-item">
            <Link class="nav-link d-inline" :href="route('dashboard')">
              {{ translations.Home }}
            </Link>
          </li>
        </ol>
      </nav>
    </div>
    <!-- End Breadcrumb -->

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ translations.add_new_account }}</h5>

              <!-- General Form -->
              <form @submit.prevent="store" class="row g-3">
              
                <div class="row mb-3">
                  <label for="inputName" class="col-sm-2 col-form-label">{{ translations.user_name }}</label>
                  <div class="col-sm-10">
                    <input
                      id="inputName"
                      type="text"
                      class="form-control"
                      :placeholder="translations.user_name"
                      v-model="form.user_name"
                    />
                    <InputError :message="form.errors.user_name" />
                  </div>
                </div>

  
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label"> {{ translations.platform }}</label>
                  <div class="col-sm-10">
                    <select name="platformList[]" class="form-control border"  v-model="form.platform">
                      <option value="" disabled> {{ translations.platform }}</option>
                      <option v-for="platform in platformList" :key="platform" :value="platform">
                        {{ platform }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNote" class="col-sm-2 col-form-label">{{ translations.note }}</label>
                  <div class="col-sm-10">
                    <input
                      id="inputNote"
                      type="text"
                      class="form-control"
                      :placeholder="translations.note"
                      v-model="form.note"
                    />
                    <InputError :message="form.errors.note" />
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputdate" class="col-sm-2 col-form-label">{{ translations.date }}</label>
                  <div class="col-sm-10">
                    <input
                      id="inputdate"
                      type="date"
                      class="form-control"
                      :placeholder="translations.date"
                      v-model="form.create"
                    />
                    <InputError :message="form.errors.date" />
                  </div>
                </div>
                <!-- Image -->
                <!-- <div class="row mb-3">
                  <label for="inputImage" class="col-sm-2 col-form-label">{{ translations.image }}</label>
                  <div class="col-sm-10">
                    <input
                      id="inputImage"
                      type="file"
                      @input="form.image = $event.target.files[0]"
                    />
                    <progress
                      v-if="form.progress"
                      :value="form.progress.percentage"
                      max="100"
                    >
                      {{ form.progress.percentage }}%
                    </progress>
                  </div>
                </div> -->

                <!-- Submit Button -->
                <div class="text-center">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="show_loader"
                  >
                    {{ translations.save }}
                    <i
                      class="bi bi-save"
                      v-if="!show_loader"
                    ></i>
                    <span
                      class="spinner-border spinner-border-sm"
                      role="status"
                      aria-hidden="true"
                      v-if="show_loader"
                    ></span>
                  </button>
                </div>
              </form>
              <!-- End General Form -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const show_loader = ref(false);

const props = defineProps({

  translations: Object,
});
let platformList = ['facebook', 'instagram', 'twitter']

const form = useForm({
  user_name: '',
  platform: '',
  create: '',
  note:'',
  image: null,
});

const store = () => {
  show_loader.value = true;
  form.post(route('accounts.store'), {
    onSuccess: () => {
      show_loader.value = false;
    },
    onError: () => {
      show_loader.value = false;
    },
  });
};
</script>
  