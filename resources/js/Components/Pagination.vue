<template>
  <nav
    v-show="totalPages > 1"
  >
    <ul class="pagination">
      <li class="page-item" :class="{ disabled: !hasPrevPage }">
        <slot name="prev" :hasPrevPage="hasPrevPage" :prevPage="localValue - 1">
          <button
            class="page-link"
            aria-label="Previous"
            @click.prevent="prev()"
          >
            <span aria-hidden="true"> Prev </span>
          </button>
        </slot>
      </li>
      <li
        v-for="page in pageBullets"
        :key="page"
        :class="{ active: localValue === page }"
        class="page-item"
      >
        <slot name="pageNum" :page="page">
            <a class="page-link" 
                href="" 
                @click.prevent="goTo(page)">
                {{ page }}
            </a>
        </slot>
      </li>
      <li class="page-item" :class="{ disabled: !hasNextPage}">
        <slot name="next" :hasNextPage="hasNextPage" :nextPage="localValue + 1">
          <button
            class="page-link"
            :disabled="!hasNextPage"
            aria-label="Next"
            @click.prevent="next()"
          >
            <span aria-hidden="true"> Next </span>
          </button>
        </slot>
      </li>
    </ul>
  </nav>
</template>
<script setup>
    import { computed, onMounted, ref, watch } from 'vue';

    const props = defineProps({
        currentPage: {
            type: Number,
            required: false,
            default: 0,
        },
        total: {
            type: Number,
            required: false,
            default: 0,
        },
        perPage: {
            type: Number,
            required: false,
            default: 10,
        },
        maxBulletPages: {
            type: Number,
            default: 5,
        }
    });

    const emit = defineEmits(['update:currentPage']);
    const localValue = ref(1);

    const totalPages = computed(() => {
        return Math.ceil(props.total / props.perPage);
    });

    const hasPrevPage = computed(() => {
        return localValue.value > 1;
    });

    const hasNextPage = computed(() => {
        return localValue.value < totalPages.value;
    });

    const pageBullets = computed(() => {
        const fromPage =
            localValue.value > Math.floor(props.maxBulletPages / 2)
            ? localValue.value - Math.floor(props.maxBulletPages / 2)
            : 1;
        const toPage = Math.min(
            totalPages.value + 1,
            fromPage + props.maxBulletPages
        );

        const pages = [];
        for (let ctr = fromPage; ctr < toPage; ctr += 1) {
            pages.push(ctr);
        }

        return pages;
    });

    const prev = () => {
        if (!hasPrevPage) return;
        localValue.value -= 1;
    }

    const next = () => {
      if (!hasNextPage.value) return;
      localValue.value += 1;
    }

    const goTo = (page) => {
      localValue.value = page;
    }

    /* Watchers */
    watch(
        () => localValue.value,
        (value) => {
            emit('update:currentPage', value);
        }
    );

    onMounted(() => {
        localValue.value = props.currentPage;
    })
    
</script>