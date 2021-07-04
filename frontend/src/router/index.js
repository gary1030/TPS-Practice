//import Vue from "vue";
import { createRouter, createWebHistory } from "vue-router";
import LookUpClient from "../views/LookUpClient";
import LookUpGood from "../views/LookUpGood";
import Home from "../views/Home";

export const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      component: Home,
    },
    {
      path: "/LookUpClient",
      component: LookUpClient,
    },
    {
      path: "/LookUpGood",
      component: LookUpGood,
    },
  ],
});
