<template>
  <div class="quantity-selector mb-4">
    <label class="font-weight-bold mb-2">Quantity:</label>
    <div class="d-flex align-items-center">
      <!-- Decrease Button -->
      <button 
        class="btn btn-outline-secondary rounded-circle"
        :class="{ 'opacity-50': quantity <= 1 }"
        @click="decreaseQuantity"
        :disabled="quantity <= 1"
        style="width: 40px; height: 40px;"
        title="Decrease quantity"
        aria-label="Decrease quantity"
      >
        <i class="fas fa-minus"></i>
      </button>

      <!-- Quantity Input -->
      <input 
        v-model.number="quantity"
        type="number"
        class="form-control text-center mx-2"
        :min="1"
        :max="maxStock"
        style="width: 80px;"
        title="Enter desired quantity"
        aria-label="Product quantity"
        @change="validateQuantity"
        @keydown="handleKeydown"
      >

      <!-- Increase Button -->
      <button 
        class="btn btn-outline-secondary rounded-circle"
        :class="{ 'opacity-50': quantity >= maxStock }"
        @click="increaseQuantity"
        :disabled="quantity >= maxStock"
        style="width: 40px; height: 40px;"
        title="Increase quantity"
        aria-label="Increase quantity"
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>

    <!-- Feedback Message -->
    <div v-if="feedbackMessage" class="feedback-message mt-2" :class="feedbackClass">
      <small>{{ feedbackMessage }}</small>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QuantitySelector',
  props: {
    maxStock: {
      type: Number,
      required: true,
      default: 1
    },
    initialQuantity: {
      type: Number,
      default: 1
    }
  },
  data() {
    return {
      quantity: this.initialQuantity,
      feedbackMessage: '',
      feedbackClass: ''
    };
  },
  watch: {
    quantity(newValue) {
      // Emit the quantity change to parent component
      this.$emit('quantity-changed', newValue);
    }
  },
  methods: {
    decreaseQuantity() {
      if (this.quantity > 1) {
        this.quantity--;
        this.showFeedback('Quantity decreased', 'success');
        this.triggerAnimation(event.target);
      } else {
        this.showFeedback('Minimum quantity reached', 'warning');
        this.triggerAnimation(event.target, 'danger');
      }
    },

    increaseQuantity() {
      if (this.quantity < this.maxStock) {
        this.quantity++;
        this.showFeedback('Quantity increased', 'success');
        this.triggerAnimation(event.target);
      } else {
        this.showFeedback(`Maximum stock (${this.maxStock}) reached`, 'warning');
        this.triggerAnimation(event.target, 'danger');
        this.showStockAlert();
      }
    },

    validateQuantity() {
      if (isNaN(this.quantity) || this.quantity < 1) {
        this.quantity = 1;
        this.showFeedback('Quantity set to minimum (1)', 'info');
      } else if (this.quantity > this.maxStock) {
        this.quantity = this.maxStock;
        this.showFeedback(`Maximum stock (${this.maxStock}) applied`, 'warning');
        this.showStockAlert();
      }
    },

    handleKeydown(e) {
      if (e.key === 'ArrowUp') {
        e.preventDefault();
        this.increaseQuantity();
      } else if (e.key === 'ArrowDown') {
        e.preventDefault();
        this.decreaseQuantity();
      }
    },

    showFeedback(message, type) {
      this.feedbackMessage = message;
      this.feedbackClass = `feedback-${type}`;

      // Auto-clear feedback after 3 seconds
      setTimeout(() => {
        this.feedbackMessage = '';
        this.feedbackClass = '';
      }, 3000);
    },

    triggerAnimation(button, type = 'success') {
      button.classList.add(`btn-${type}`);
      setTimeout(() => {
        button.classList.remove(`btn-${type}`);
      }, 200);
    },

    showStockAlert() {
      // Use SweetAlert2 if available, otherwise use browser alert
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Maximum Stock Reached',
          text: `Only ${this.maxStock} units available in stock.`,
          timer: 2000,
          showConfirmButton: false
        });
      }
    },

    // Method to get current quantity (can be called from parent)
    getQuantity() {
      return this.quantity;
    },

    // Method to set quantity from parent
    setQuantity(value) {
      if (value >= 1 && value <= this.maxStock) {
        this.quantity = value;
      }
    },

    // Method to reset quantity
    resetQuantity() {
      this.quantity = this.initialQuantity;
      this.feedbackMessage = '';
      this.feedbackClass = '';
    }
  },

  mounted() {
    this.quantity = Math.max(1, Math.min(this.initialQuantity, this.maxStock));
  }
};
</script>

<style scoped>
.quantity-selector {
  display: flex;
  flex-direction: column;
}

.quantity-selector label {
  color: #333;
  font-size: 0.95rem;
  margin-bottom: 0.75rem;
}

.quantity-selector .btn {
  transition: all 0.2s ease;
  border-width: 2px;
  flex-shrink: 0;
}

.quantity-selector .btn:hover:not(:disabled) {
  transform: scale(1.05);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.quantity-selector .btn:active:not(:disabled) {
  transform: scale(0.95);
}

.quantity-selector .btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.quantity-selector input {
  border-radius: 8px;
  border: 2px solid #dee2e6;
  transition: border-color 0.3s ease;
  font-weight: 500;
}

.quantity-selector input:focus {
  border-color: #495057;
  box-shadow: 0 0 0 0.2rem rgba(73, 80, 87, 0.25);
}

.opacity-50 {
  opacity: 0.5 !important;
}

/* Feedback Messages */
.feedback-message {
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.feedback-success {
  color: #28a745;
  font-weight: 500;
}

.feedback-warning {
  color: #ffc107;
  font-weight: 500;
}

.feedback-info {
  color: #17a2b8;
  font-weight: 500;
}

.feedback-error {
  color: #dc3545;
  font-weight: 500;
}

/* Button animations */
.btn-success {
  background-color: #28a745;
  border-color: #28a745;
  color: white;
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
  color: white;
}

/* Responsive adjustments */
@media (max-width: 576px) {
  .quantity-selector label {
    font-size: 0.9rem;
  }

  .quantity-selector input {
    width: 70px !important;
  }

  .quantity-selector .btn {
    width: 36px !important;
    height: 36px !important;
  }
}
</style>
