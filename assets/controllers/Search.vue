<template>
    <div class="container mt-4">
        <div class="mb-3">
            <div class="form-group">
                <label for="searchName">Имя</label>
                <input class="form-control" id="searchName" type="text" v-model="searchName" @input="search">
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="searchInitialDirectory">Торрент директория</label>
                        <input class="form-control" id="searchInitialDirectory" type="text" v-model="searchInitialDirectory" @input="search">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="searchFinalDirectory">VOD директория</label>
                        <input class="form-control" id="searchFinalDirectory" type="text" v-model="searchFinalDirectory" @input="search">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="searchKpId">Кинопоиск ID</label>
                        <input class="form-control" id="searchKpId" type="number" v-model="searchKpId" @input="search">
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label for="searchSmartyId">Smarty ID</label>
                        <input class="form-control" id="searchSmartyId" type="number" v-model="searchSmartyId" @input="search">
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label for="searchTranscodingStatus">Статус транскодирования</label>
                        <select class="form-control" id="searchTranscodingStatus" v-model="searchTranscodingStatus" @change="search">
                            <option value="">Выбирете статус</option>
                            <option value="in-progress">В процессе</option>
                            <option value="Завершено">Завершено</option>
                            <option value="Ошибка">Ошибка</option>
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label for="searchDisk">Диск</label>
                        <select class="form-control" id="searchDisk" v-model="searchDisk" @change="search">
                            <option value="">Выбирете диск</option>
                            <option value="1">HDD 1</option>
                            <option value="11">HDD 11</option>
                            <option value="14">HDD 14</option>
                            <option value="15">HDD 15</option>
                            <option value="20">HDD 20</option>
                            <option value="21">HDD 21</option>
                            <option value="3">HDD 3</option>
                            <option value="5">HDD 5</option>
                            <option value="8">HDD 8</option>
                            <option value="9">HDD 9</option>
                        </select>
                    </div>
                </div>
            </div>

            
        </div>

            <div class="form-group">
                <label for="searchAddedBy">Добавлено</label>
                <input class="form-control" id="searchAddedBy" type="text" v-model="searchAddedBy" @input="search">
            </div>
    
      <span v-if="loading">Идет поиск...</span>
      <table v-if="!loading" class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>kpId</th>
          <th>Name</th>
          <th>Original Name</th>
          <th>Is Season</th>
          <th>hdd</th>
          <th>Status</th>
          <th>pid</th>
          <th>userSubmittedBy</th>
          <th>updateAt</th>
          <th>errorMessage</th>
        </tr>
      </thead>
      <tbody v-if="response.length > 0">
        <tr v-for="item in response" :key="item.id">
          <td>{{ item.id }}</td>
          <td>{{ item.kpId }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.nameOrig }}</td>
          <td>{{ item.isSeason }}</td>
          <td>{{ item.hdd }}</td>
          <td>{{ item.Status }}</td>
          <td>{{ item.pid }}</td>
          <td>{{ item.UserSubmittedBy }}</td>
          <td>{{ item.UpdateAt }}</td>
          <td>{{ item.error_message }}</td>
          <td>
                <div v-for="smartyId in item.smartyId" :key="smartyId.id"><a :href="'http://mi-smarty.mycentra.ru/tvmiddleware/video/edit?checked%5B%5D=' + smartyId.id" target="_blank">{{ smartyId.id }}</a><br></div>
            
        </td>
    </tr>
        </tr>
      </tbody>
    </table>
      <table>
        <thead>
          <tr>
            <th>Result 1</th>
            <th>Result 2</th>
            <!-- Add more table headings as needed -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="result in searchResults" :key="result.id" @click="showDetails(result)">
            <td>{{ result.field1 }}</td>
            <td>{{ result.field2 }}</td>
            <!-- Add more table data fields as needed -->
          </tr>
        </tbody>
      </table>
    
      <div v-if="selectedResult">
        <h2>Selected Result Details</h2>
        <!-- Display detailed information about the selected result -->
      </div>
    </div>
</div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return {
        searchName: '',
        searchInitialDirectory: '',
        searchFinalDirectory: '',
        searchKpId: '',
        searchSmartyId: '',
        searchTranscodingStatus: '',
        searchDisk: '',
        searchAddedBy: '',
        searchResults: [],
        loading: false,
        selectedResult: null,
        response: []
      };
    },
    methods: {
      search() {
        let searchData = {
            searchName: this.searchName,
            searchInitialDirectory: this.searchInitialDirectory,
            searchFinalDirectory: this.searchFinalDirectory,
            searchKpId: this.searchKpId,
            searchSmartyId: this.searchSmartyId,
            searchTranscodingStatus: this.searchTranscodingStatus,
            searchDisk: this.searchDisk,
            searchAddedBy: this.searchAddedBy
        };

        axios.post('/api/search', searchData)
            .then(response => {
                console.log(response.data);
                this.response = response.data;
            })
            .catch(error => {
                console.error(error);
            });
        console.log(
            searchData
            );
        // Perform search logic based on the input fields and populate searchResults
        this.loading = true;
        // Simulated loading delay
        setTimeout(() => {
          // Perform backend search using this.searchName, this.searchInitialDirectory, etc.
          // Update this.searchResults with the search results
          this.loading = false;
        }, 1000);
      },
      showDetails(result) {
        this.selectedResult = result;
      }
    }
  };
  </script>