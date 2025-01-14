<template>
    <div v-show="isOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-30">
        <transition name="modal-fade" @before-enter="beforeEnter" @enter="enter" @leave="leave">
            <div v-if="isOpen">
                <div class="relative bg-white p-4 rounded-2xl shadow-lg mx-4">
                    <slot></slot>
                </div>
            </div>
        </transition>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    isOpen: Boolean
  });
  
  const emit = defineEmits(['update:isOpen']);
  
  const close = () => {
    emit('update:isOpen', false);
  };
  
  const beforeEnter = (el) => {
    el.style.transform = 'translateY(100%)';
  };
  
  const enter = (el, done) => {
    el.offsetHeight; // Trigger reflow
    el.style.transition = 'transform 0.3s ease-out';
    el.style.transform = 'translateY(0)';
    done();
  };
  
  const leave = (el, done) => {
    el.style.transition = 'transform 0.3s ease-in';
    el.style.transform = 'translateY(100%)';
    done();
  };
  </script>
  
  <style>
  .modal-fade-enter-active, .modal-fade-leave-active {
    opacity: 1;
  }
  .modal-fade-enter, .modal-fade-leave-to /* .modal-fade-leave-active in <2.1.8 */ {
    opacity: 0;
  }
  </style>