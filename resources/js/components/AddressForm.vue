<script setup>
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

defineProps({
    address: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:address']);

const updateField = (field, value) => {
    emit('update:address', { ...defineProps().address, [field]: value });
};
</script>

<template>
    <div class="space-y-4">
        <div class="space-y-2">
            <Label for="shipping-address">Street Address</Label>
            <Input
                id="shipping-address"
                :value="address.shipping_address"
                @input="(e) => updateField('shipping_address', e.target.value)"
                placeholder="123 Main Street"
                required
            />
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="space-y-2">
                <Label for="shipping-city">City</Label>
                <Input
                    id="shipping-city"
                    :value="address.shipping_city"
                    @input="(e) => updateField('shipping_city', e.target.value)"
                    placeholder="Berlin"
                    required
                />
            </div>
            <div class="space-y-2">
                <Label for="shipping-state">State</Label>
                <Input
                    id="shipping-state"
                    :value="address.shipping_state"
                    @input="(e) => updateField('shipping_state', e.target.value)"
                    placeholder="Berlin"
                    required
                />
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="space-y-2">
                <Label for="shipping-country">Country</Label>
                <Input
                    id="shipping-country"
                    :value="address.shipping_country"
                    @input="(e) => updateField('shipping_country', e.target.value)"
                    placeholder="Germany"
                    required
                />
            </div>
            <div class="space-y-2">
                <Label for="shipping-pin-code">PIN Code</Label>
                <Input
                    id="shipping-pin-code"
                    :value="address.shipping_pin_code"
                    @input="(e) => updateField('shipping_pin_code', e.target.value)"
                    placeholder="10115"
                    required
                />
            </div>
        </div>
    </div>
</template>
