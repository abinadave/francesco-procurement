<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <a :disabled="checkedNotifications.length === 0" @click="markUnreadSelected" class="btn btn-default btn-xs" style="cursor: pointer; margin-top: -20px">
                  <i class="glyphicon glyphicon-ok"></i>&nbsp;
                  Mark as Read &nbsp;
                  <span v-show="checkedNotifications.length">
                    [ {{checkedNotifications.length}} ]
                  </span>
                </a>
                <div class="list-group">
                  <a class="list-group-item disabled">
                    Your Notification will appear below <span class="badge" style="float: left; margin-right: 10px">{{ notifications.length }}</span>
                  </a>
                  <a v-for="notif in notifications" href="#" class="list-group-item">
                      <span :class="{ 'readed-notif' : ifThisIsReaded(notif) }">{{ notif.notification_body }}</span><br>
                      <b :class="{ 'readed-notif' : ifThisIsReaded(notif) === true, 'text-info': true }">{{ formatDate(notif.datetime) }}</b>
                      <span v-if="ifThisIsReaded(notif) === false">
                           <input :value="notif.id" v-model="checkedNotifications" type="checkbox" class="pull-right" style="margin-top: -15px">
                      </span>
                      <span v-else>
                          <i class="pull-right" style="margin-top: -15px; font-weight: bolder">Readed</i>
                      </span>
                     
                  </a>
                </div>
                read_notifications: {{ read_notifications.length }}
            </div>
        </div>
    </div>
</template>
<style scoped>
    .readed-notif {
        /*text-decoration: line-through;*/
        opacity: 0.6;
    }
</style>
<script>
    import moment from 'moment'
    export default {
        mounted() {
            this.$store.commit('FETCH_MY_NOTIFICATIONS');
            this.$store.commit('FETCH_READ_NOTIF');
        },
        data(){
            return {
                checkedNotifications: []
            }
        },
        methods: {
            ifThisIsReaded(notification){
                let self = this;
                let rs = _.filter(self.read_notifications, { notif_id: Number(notification.id)});
                return (rs.length > 0) ? true : false;
            },
            markUnreadSelected(){
                let self = this;
                let ids = self.checkedNotifications;
                self.$http.post('read_notification', {
                    ids
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.checkedNotifications=[];
                        self.$store.commit({
                            type: 'PUSH_READ_NOTIFS',
                            json
                        });
                    }
                }, (resp) => {
                    console.log(resp)
                })
            },
            formatDate(datetime){
                return moment(datetime).format('MMMM DD, YYYY hh:mm a') + ' (' + moment(datetime).fromNow() +')';
            }
        },
        computed: {
            notifications(){
                return this.$store.getters.notifications;
            },
            read_notifications(){
                return this.$store.getters.read_notifications;
            }
        }
    }
</script>
