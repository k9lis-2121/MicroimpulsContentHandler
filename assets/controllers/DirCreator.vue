<template>
    <div>

      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="kinopoiskId">Кинопоиск ID</label>
          <input type="text" class="form-control" id="kinopoiskId" v-model="formData.kinopoiskId" required>
        </div>
        <div class="form-group">
          <label for="title">Название</label>
          <input type="text" class="form-control" id="title" v-model="formData.title" required>
        </div>
        <div class="form-group">
          <label for="tlink">Ссылка на торрент</label>
          <input type="text" class="form-control" id="tlink" v-model="formData.tlink" required>
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
          <!-- <div class="form-group">
            <label for="seasonCount">Количество сезонов</label>
            <input type="number" class="form-control" id="seasonCount" v-model="formData.seasonCount" required>
          </div> -->
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
        <button type="submit" class="btn btn-primary">Отправить</button>
      </form>
      
      <div v-if="formData">
        <h3>Заполненные данные:</h3>
        <pre>{{ JSON.stringify(formData) }}</pre>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
    import Vue from 'vue';
  
  export default {
    data() {
      return {
	styles: {
        backgroundColor: 'blue',
        color: 'white',
        fontSize: '16px'
      },
        formData: {
          kinopoiskId: '',
          title: '',
          isSerial: false,
          isTrailler: false,
          seasonCount: [],
          sameEpisodesCount: false,
          episodesCount: {},
          sameEpisodes: {},
          tlink: '',
        },
        file: null,
        seasonArray: [],
        seasonArrayConvert: [],
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
tlink: this.formData.tlink,
seasonCount: this.formData.seasonCount,
sameEpisodesCount: this.formData.sameEpisodesCount,
sameEpisodes: this.formData.sameEpisodes,
episodesCount: this.formData.episodesCount
};

const json = JSON.stringify(data);

const formData = new FormData();
formData.append('data', json);
formData.append('file', this.file);

axios.post('/api/maker/dir', formData, {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
})

  // submitForm() {
  //   const formData = new FormData();
  //   const episodesCountString = this.createSeasonArrayToString();
  //   console.log(this.formData.episodesCount);
  //   formData.append('kinopoiskId', this.formData.kinopoiskId);
  //   formData.append('title', this.formData.title);
  //   formData.append('isSerial', this.formData.isSerial);
  //   formData.append('isTrailler', this.formData.isTrailler);
  //   formData.append('tlink', this.formData.tlink);
  //   formData.append('seasonCount', this.formData.seasonCount);
  //   formData.append('sameEpisodesCount', this.formData.sameEpisodesCount);
  //   formData.append('sameEpisodes', this.formData.sameEpisodes);
  //   formData.append('episodesCount', this.formData.episodesCount);
  //   formData.append('file', this.file); // Добавление выбранного файла

  //   axios
  //     .post('/api/maker/dir', formData,  {
  //   headers: {
  //     'Content-Type': 'multipart/form-data'
  //   }
  // })
      .then((response) => {
        // Обработка успешного ответа
        // console.log(response.data);
        this.isFormSubmitted = true;
      })
      .catch((error) => {
        // Обработка ошибки
        console.error(error);
      });
  },

  // submitForm() {
  //       axios.post('/api/maker/dir', this.formData)
  //         .then(response => {
  //           // Обработка успешного ответа
  //           console.log(response.data);
  //           this.isFormSubmitted = true;
  //         })
  //         .catch(error => {
  //           // Обработка ошибки
  //           console.error(error);
  //         });
  //     },

    },
  };
  </script>
