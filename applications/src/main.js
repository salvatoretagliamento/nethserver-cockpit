import Vue from "vue";
import VueResource from "vue-resource";
import VueI18n from "vue-i18n";
import VueGoodTable from "vue-good-table";
import BootstrapVue from 'bootstrap-vue'

import App from "./App.vue";
import router from "./router/index";
import languages from "./i18n/lang";
import filters from "./filters/filters";

Vue.config.productionTip = false;
Vue.use(VueResource);
Vue.use(VueI18n);
Vue.use(VueGoodTable);
Vue.use(BootstrapVue);

// configure i18n
var langConf = languages.initLang();
const i18n = new VueI18n({
  locale: langConf.locale,
  messages: langConf.messages
});
var moment = require("moment");
moment.locale(langConf.locale);

// init Vue app
new Vue({
  el: "#app",
  router,
  i18n,
  render: h => h(App),
  currentLocale: langConf.locale
});