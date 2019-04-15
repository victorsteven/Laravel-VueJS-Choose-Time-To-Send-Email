<template>
  <div>
      <div class="card col-md-8 offset-md-2" style="margin-top: 20px;">
          <div class="card-header">
            <div style="display:flex; justify-content: space-between;">
                <h2>Email List</h2>
                <a id="create_form_icon" href="#" class="btn btn-lg btn-primary" @click="openModal">
                    <i class="plus icon"></i>Send Email
                </a>
            </div>
          </div>
            <div style="">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td style="width: 33%">{{user.name}}</td>
                        <td style="width: 43%">{{user.email}}</td>
                        <td style="width: 23%">{{ user.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>

      <div class="card col-md-8 offset-md-2" style="margin-top: 30px;">
        <div class="card-header">
        <h2>Sent Messages</h2>
        </div>
        <div style="margin-top: 20px;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Body</th>
                        <th>Delivered</th>
                        <th>Sent Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="message in messages" :key="message.id">
                        <td style="width: 30%">{{message.title}}</td>
                        <td style="width: 43%">{{message.body}}</td>
                        <td style="width: 10%">{{message.delivered}}</td>
                        <td style="width: 15%">{{ message.send_date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>


    <!-- The Modal -->
    <div class="modal fade" id="create_form_modal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"  id="addNewLabel">Send Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="sendEmail">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title of Email</label>
                        <input v-model="title" type="text" name="title" placeholder="Email Title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email body</label>
                        <textarea v-model="body" name="body" id="body" placeholder="Body of message" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 10px;">When to Send:</label>
                        <div class="form-control">
                            <span style="margin-right: 20px;">
                                <input type="radio" name="sending" value="now" v-model="item" checked>
                                <label>Send Now</label>
                            </span>
                            <span>
                                <input type="radio" name="sending" value="later" v-model="item">
                                <label>Send Later</label>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-control" v-if="item === 'later'">
                    <VueCtkDateTimePicker :no-button-now = "true"	 v-model="send_date" />
                </div>

                <div class="modal-footer">
                    <button
                        :disabled="disabled" v-if="loading && item === 'now'"
                        type="submit" class="btn  btn-success">
                        Sending Email...
                    </button>
                    <button
                        :disabled="disabled" 
                        v-if="!loading && item === 'now'"
                        type="submit" class="btn  btn-success">
                        Send Email
                    </button>
                    <button  
                        v-if="!loading && item === 'later'"
                        :disabled="disabled"
                        type="submit" 
                        class="btn  btn-success"
                        >
                        Send Later
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
  </div>
</template>

<script>
import axios from 'axios'

export default {

  data(){
      return {
          users: [],
          messages: [],
          send_now: true,
          loading: false,
          title: '',
          body: '',
          send_date: '',
          item: 'now'
      }
  },

  created(){
      this.getUsers();
      this.getMessages();
      console.log('these are the users: ', this.users)
},

  computed: {
      disabled(){
      return  this.title === '' || !this.title || this.body === '' || !this.body || this.loading 
    },
  },

  methods: {
    
    getUsers(){
        axios.get('/get_users').then(res => {
            this.users = res.data;
        })
    },

    getMessages(){
         axios.get('/get_messages').then(res => {
            this.messages = res.data;
        })
    },

    openModal(){
        $('#create_form_modal').modal('show');
    },
    
    sendEmail () {
      this.loading = true

      const sendData = { title: this.title, body: this.body, send_date: this.send_date, item: this.item }

    axios.post('/notifications', sendData)
        .then((resp) => {
          console.log(resp);
            $('#create_form_modal').modal('hide');

            if (this.item == 'now') {
                swal(
                    'Sent!',
                    'Email Sent to Users',
                    'success'
                )
            } else {
                swal(
                    'Scheduled!',
                    'Email Scheduled! To Be Sent Later',
                    'success'
                )
            }
          this.title = '';
          this.body = '';
          console.log(resp)
          this.loading = false 

          setTimeout(() => {
            window.location.reload(window.origin + '/');
          }, 5000)
        })
        .catch(error => console.log(error))
    },
  }
}
</script>

<style scoped>
.btn {
  margin-right: 10px;
  margin-bottom: 10px;
}
</style>
