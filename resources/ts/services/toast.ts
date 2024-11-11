// src/plugins/toast.ts
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';


// Define a type for the toast types
type ToastType = 'success' | 'error' | 'info' | 'warning';

// Utility function to show toast messages
export const showToast = (type: ToastType, message: string) => {
  switch (type) {
    case 'success':
      toast.success(message);
      break;
    case 'error':
      toast.error(message);
      break;
    case 'info':
      toast.info(message);
      break;
    case 'warning':
      toast.warning(message);
      break;
    default:
      toast(message); // Fallback if no valid type is provided
  }
};
