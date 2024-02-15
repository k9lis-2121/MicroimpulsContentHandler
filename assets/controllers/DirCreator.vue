<template>
    <div>
  <div v-if="!isFormSubmitted" class="container">
      <form @submit.prevent="submitForm">

        <div class="row">
  <div class="col-6">
    <div class="row">
      <div class="col-4">
        <label class="form-check-label">Загрузить в смарти</label>
      </div>
      <div class="col-1">
        <div class="form-check">
          <input type="radio" class="form-check-input" id="uploadToSmartyYes" value="yes" v-model="formData.uploadToSmarty" checked>
          <label class="form-check-label" for="uploadToSmartyYes">Да</label>
        </div>
      </div>
      <div class="col-1">
        <div class="form-check">
          <input type="radio" class="form-check-input" id="uploadToSmartyNo" value="no" v-model="formData.uploadToSmarty">
          <label class="form-check-label" for="uploadToSmartyNo">Нет</label>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-6">
    <div class="row">
      <div class="col-4">
        <label class="form-check-label">Создать директории</label>
      </div>
      <div class="col-1">
        <div class="form-check">
          <input type="radio" class="form-check-input" id="createDirectoryYes" value="yes" v-model="formData.createDirectory" checked>
          <label class="form-check-label" for="createDirectoryYes">Да</label>
        </div>
      </div>
      <div class="col-1">
        <div class="form-check">
          <input type="radio" class="form-check-input" id="createDirectoryNo" value="no" v-model="formData.createDirectory">
          <label class="form-check-label" for="createDirectoryNo">Нет</label>
        </div>
      </div>
    </div>
  </div>
</div>

        <div class="form-group">
          <label for="kinopoiskId">Кинопоиск ID</label>
          <input type="text" class="form-control" id="kinopoiskId" v-model="formData.kinopoiskId" required>
        </div>
        <div class="form-group">
          <label for="title">Название</label>
          <input type="text" class="form-control" id="title" v-model="formData.title" required>
        </div>
        <div class="form-group">
          <label for="file">Загрузить файл</label>
          <input type="file" id="file" ref="fileInput" @change="handleFileChange">
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="isSerial" v-model="formData.isSerial">
          <label class="form-check-label" for="isSerial">Сериал</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="isTrailler" v-model="formData.isTrailler">
          <label class="form-check-label" for="isTrailler">Трейлер</label>
        </div>
        <div v-if="formData.isSerial && !formData.isTrailler">
            <div class="form-group">
            <label for="seasonCount">Количество сезонов</label>
            <input type="number" class="form-control" id="seasonCount" v-model="formData.seasonCount" required @input="createSeasonArray">
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="sameEpisodesCount" v-model="formData.sameEpisodesCount">
            <label class="form-check-label" for="sameEpisodesCount">Одинаковое количество серий</label>
          </div>
          {{ formData.seasonCount }}
          <div v-if="!formData.sameEpisodesCount">
            <div v-for="index in seasonArray" :key="index">
              <label>Сезон {{ index }}</label>
              <input type="number" class="form-control" v-model="formData.episodesCount[index]" placeholder="Количество серий">
            </div>

            
          </div>
          <div v-else>
            <div class="form-group">
              <label for="sameEpisodes">Количество серий в сезонах</label>
              <input type="number" class="form-control" id="sameEpisodes" v-model="formData.sameEpisodes" @input="createSeasonArrayConvert" placeholder="Количество серий">
            </div>
          </div>
        
        </div>
          <!-- Новый чекбокс для транскодирования -->
          <div class="form-check mt-3">
          <input type="checkbox" class="form-check-input" id="transcode" v-model="formData.transcode">
          <label class="form-check-label" for="transcode">Транскодировать</label>
        </div>

        <!-- Поле для ввода имени файла, появляется если transcode == true -->
        <div v-if="formData.transcode" class="form-group mt-3">
          <label for="fileName">Имя файла</label>
          <input type="text" class="form-control" id="fileName" v-model="formData.fileName">
        </div>




         <!-- Новый чекбокс для транскодирования -->
        <div class="form-check mt-3">
          <input type="checkbox" class="form-check-input" id="handhdd" v-model="formData.handhdd">
          <label class="form-check-label" for="handhdd">Указать диск в ручную</label>
        </div>

        <!-- Поле для ввода имени файла, появляется если transcode == true -->
        <div v-if="formData.handhdd" class="form-group mt-3">
          <label for="selectedDisk">Номер диска</label>
          <input type="text" class="form-control" id="selectedDisk" v-model="formData.selectedDisk">
        </div>




        <button type="submit" class="btn btn-primary">Отправить</button>
      </form>
      <!-- Сообщение о загрузке -->
    
      
    </div>
    <div v-if="loading">
      <h3>Загрузка...</h3>
    </div>
    <div v-if="dir">
        <h3>Путь до директории на сервере:</h3>
        <pre>{{ dir }}</pre>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import Vue from 'vue';  
  import VueToasted from 'vue-toasted';
  Vue.use(VueToasted);

  export default {
    mounted() {
    // Вызовите функцию для обновления статуса каждые 10 секунд при монтировании компонента
    this.resetForm();
    this.updateStatusPeriodically();
  },
    data() {
      return {
	styles: {
        backgroundColor: 'blue',
        color: 'white',
        fontSize: '16px'
      },
      initialFormData: () => ({
        kinopoiskId: '',
        title: '',
        isSerial: false,
        isTrailler: false,
        seasonCount: '',
        sameEpisodesCount: false,
        episodesCount: {},
        sameEpisodes: {},
        uploadToSmarty: 'yes',
        createDirectory: 'yes',
        transcode: false,
        fileName: '',
        handhdd: false,
        selectedDisk: 0,
      }),
        formData: {
          kinopoiskId: '',
          title: '',
          isSerial: false,
          isTrailler: false,
          seasonCount: [],
          sameEpisodesCount: false,
          episodesCount: {},
          sameEpisodes: {},
          uploadToSmarty: 'yes',
          createDirectory: 'yes',
          transcode: false, // Добавлено для транскодирования
          
        handhdd: false,
        selectedDisk: 0,
        fileName: '' // Имя файла при транскодировании
        },
        dir: '',
        file: null,
        seasonArray: [],
        seasonArrayConvert: [],
        loading: false,
        isFormSubmitted: false,
      };
    },
    methods: {
        createSeasonArray() {
    const count = parseInt(this.formData.seasonCount);
    this.seasonArray = Array.from({ length: count }, (_, index) => index + 1);
  },
  createSeasonArrayConvert() {
    const count = parseInt(this.formData.seasonCount);
    const seasonObject = {};

    for (let i = 1; i <= count; i++) {
        seasonObject[i] = this.formData.sameEpisodes;
    }

    this.formData.episodesCount = seasonObject;
  },
  handleFileChange(event) {
    this.file = event.target.files[0];
  },
  createSeasonArrayToString() {
  const count = parseInt(this.formData.seasonCount);
  const seasonArray = Array.from({ length: count }, (_, index) => index + 1);
  const seasonString = seasonArray.join(',');

  return seasonString;
},
createEpisodeArrayToString() {
  const count = parseInt(this.formData.seasonCount);
  const seasonArray = Array.from({ length: count }, (_, index) => index + 1);
  const seasonString = seasonArray.join(',');

  return seasonString;
},


submitForm() {
const data = {
kinopoiskId: this.formData.kinopoiskId,
title: this.formData.title,
isSerial: this.formData.isSerial,
isTrailler: this.formData.isTrailler,
seasonCount: this.formData.seasonCount,
sameEpisodesCount: this.formData.sameEpisodesCount,
sameEpisodes: this.formData.sameEpisodes,
episodesCount: this.formData.episodesCount,
uploadToSmarty: this.formData.uploadToSmarty,
createDirectory: this.formData.createDirectory,
transcode: this.formData.transcode,
fileName: this.formData.fileName,
handhdd: this.formData.handhdd,
selectedDisk: this.formData.selectedDisk
};

  this.loading = true;
      const formData = new FormData();
      formData.append('data', JSON.stringify(this.formData));
      if (this.file) {
        formData.append('file', this.file);
      }

const json = JSON.stringify(data);

// const formData = new FormData();
formData.append('data', json);
formData.append('file', this.file);

this.isFormSubmitted = true;
axios.post('/api/maker/dir', formData, {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
    
}

)

      .then((response) => {
        // Обработка успешного ответа
        // console.log(response.data);
        
        this.isFormSubmitted = false;
        this.dir = response.data.contentDirectory;
        this.showToast(response);
        this.resetForm(); // Сброс формы
      })
      .catch((error) => {
        // Обработка ошибки
        console.error(error);
        this.showToast("Ошибка при отправке данных");
      });
  },

  resetForm() {
      // Сброс formData к начальным значениям
      this.formData = this.initialFormData();
      this.file = null;
      this.seasonArray = [];
      this.isFormSubmitted = false;
      // Обнуление асинхронного индикатора загрузки, если это необходимо
      setTimeout(() => this.loading = false, 0);
    },

  updateStatus() {
  axios.get('/api/status')
    .then((response) => {
      this.showToast(response);
    })
    .catch((error) => {
      console.error(error);
    });
},

showToast(response) {
  const message = response.data.message;
  if (message === true) {
    // Отображаем toast с содержимым из ответа
    const messageTitle = response.data.messageTitle;
    const messageBody = response.data.messageBody;

    this.$toasted.show(messageBody, {
      position: 'top-right',
      duration: 5000,
      action: {
        text: messageTitle,
        onClick: (e, toastObject) => {
          toastObject.goAway(0);
        },
      },
    });
  }
},
    // Функция для обновления статуса каждые 10 секунд
    updateStatusPeriodically() {
      setInterval(() => {
        this.updateStatus();
      }, 10000);
    },

    },
  };
  </script>
