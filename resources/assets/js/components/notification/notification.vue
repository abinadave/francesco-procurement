<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="list-group">
                  <a href="#" class="list-group-item disabled">
                    Your Notification will appear below <span class="badge" style="float: left; margin-right: 10px">{{ notifications.length }}</span>
                  </a>
                  <a v-for="notif in notifications" href="#" class="list-group-item">
                      {{ notif.notification_body }}<br>
                      <b class="text-info">{{ formatDate(notif.datetime) }}</b>
                  </a>
                </div>
            </div>
                
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        mounted() {
            this.$store.commit('FETCH_MY_NOTIFICATIONS');
        },
        methods: {
            formatDate(datetime){
                return moment(datetime).format('MMMM DD, YYYY hh:mm a') + ' (' + moment(datetime).fromNow() +')';
            }
        },
        computed: {
            notifications(){
                return this.$store.getters.notifications;
            }
        }
    }
</script>
