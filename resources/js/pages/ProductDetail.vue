<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Head, router } from '@inertiajs/vue3';
import { ShoppingCart, ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const breadcrumbs = [
    {
        title: 'Shop',
        href: '/shop',
    },
    {
        title: props.product.name,
        href: `/products/${props.product.id}`,
    },
];

const formatPrice = (price) => {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};

const addToCart = () => {
    router.post('/cart/add', {
        product_id: props.product.id,
        quantity: 1,
    }, {
        preserveScroll: true,
    });
};

const goBack = () => {
    router.visit('/shop');
};

// Image gallery logic
const selectedImageIndex = ref(0);
const images = props.product.images || [];

const selectImage = (index) => {
    selectedImageIndex.value = index;
};

const currentImage = () => {
    return images.length > 0 ? images[selectedImageIndex.value] : (props.product.image || '');
};
</script>

<template>
    <Head :title="product.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <Button variant="ghost" @click="goBack" class="w-fit">
                <ArrowLeft class="mr-2 h-4 w-4" />
                Back to Shop
            </Button>

            <Card>
                <CardContent class="p-6">
                    <div class="grid gap-8 md:grid-cols-2">
                        <!-- Image Gallery -->
                        <div class="flex flex-col gap-4">
                            <!-- Main Image -->
                            <div class="aspect-square overflow-hidden rounded-lg bg-muted">
                                <img
                                    :src="currentImage()"
                                    :alt="product.name"
                                    class="h-full w-full object-cover"
                                />
                            </div>

                            <!-- Thumbnail Images (only show if multiple images exist) -->
                            <div v-if="images.length > 1" class="grid grid-cols-4 gap-2">
                                <div
                                    v-for="(image, index) in images"
                                    :key="index"
                                    @click="selectImage(index)"
                                    :class="[
                                        'aspect-square cursor-pointer overflow-hidden rounded-md border-2 transition-all',
                                        selectedImageIndex === index
                                            ? 'border-primary'
                                            : 'border-transparent hover:border-gray-300'
                                    ]"
                                >
                                    <img
                                        :src="image"
                                        :alt="`${product.name} - Image ${index + 1}`"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <div>
                                <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                                <p class="mt-4 text-4xl font-bold text-primary">
                                    {{ formatPrice(product.price) }}
                                </p>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold mb-2">Description</h2>
                                <p class="text-muted-foreground leading-relaxed">
                                    {{ product.description }}
                                </p>
                            </div>

                            <div class="mt-auto">
                                <Button size="lg" class="w-full" @click="addToCart">
                                    <ShoppingCart class="mr-2 h-5 w-5" />
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
