<template>
  <div>
    <Navbar />

    <div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-md" style="font-family: 'Poppins', sans-serif">
      <h2 class="text-2xl font-bold mb-4 text-center">Account Information</h2>

      <div v-if="user">
        <!-- Display user info -->
        <div v-if="!editing" class="space-y-3 text-gray-700">
          <p><strong>Username:</strong> {{ user.username }}</p>
          <p><strong>Email:</strong> {{ user.email }}</p>
          <p><strong>Phone:</strong> {{ user.phone || '-' }}</p>
          <p><strong>Last Name:</strong> {{ user.last_name || '-' }}</p>

          <button
            @click="editing = true"
            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
          >
            Modify
          </button>
        </div>

        <!-- Modal for editing -->
        <div
          v-if="editing"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
          <div class="bg-white rounded-md p-6 w-full max-w-md relative">
            <h3 class="text-xl font-semibold mb-4 text-center">Edit Account Info</h3>

            <form @submit.prevent="updateUser" class="space-y-4">
              <div>
                <label for="username" class="block font-semibold mb-1">Username</label>
                <input
                  id="username"
                  v-model="form.username"
                  type="text"
                  class="w-full border px-3 py-2 rounded"
                  required
                />
              </div>

              <div>
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="w-full border px-3 py-2 rounded"
                  required
                />
              </div>

              <div>
                <label for="phone" class="block font-semibold mb-1">Phone</label>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="text"
                  class="w-full border px-3 py-2 rounded"
                />
              </div>

              <div>
                <label for="last_name" class="block font-semibold mb-1">Last Name</label>
                <input
                  id="last_name"
                  v-model="form.last_name"
                  type="text"
                  class="w-full border px-3 py-2 rounded"
                />
              </div>

              <div class="flex justify-between items-center mt-6">
                <button
                  type="submit"
                  :disabled="loading"
                  class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition disabled:opacity-50"
                >
                  {{ loading ? "Saving..." : "Save Changes" }}
                </button>

                <button
                  type="button"
                  @click="cancelEdit"
                  class="text-gray-600 hover:text-gray-900"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div v-else class="text-gray-500 text-center">
        Loading user information...
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Navbar from "../components/Navbar.vue";
import Swal from "sweetalert2";

const user = ref(null);
const loading = ref(false);
const editing = ref(false);

const form = ref({
  username: "",
  email: "",
  phone: "",
  last_name: "",
});

// Load user data on mount
onMounted(async () => {
  try {
    const response = await fetch("http://localhost/Sporify2/backend/entities/getUser.php", {
      method: "GET",
      credentials: "include",
    });
    const data = await response.json();

    if (data.success && data.user) {
      user.value = data.user;
      fillForm();
    } else {
      console.error("Failed to load user:", data.message);
    }
  } catch (error) {
    console.error("Error fetching user info:", error);
  }
});

// Helper: fill form from user data
function fillForm() {
  form.value.username = user.value.username || "";
  form.value.email = user.value.email || "";
  form.value.phone = user.value.phone || "";
  form.value.last_name = user.value.last_name || "";
}

// Cancel editing modal
function cancelEdit() {
  editing.value = false;
  fillForm(); // reset form to original user data
}

// Update user info submit handler
async function updateUser() {
  loading.value = true;
  try {
    const response = await fetch("http://localhost/Sporify2/backend/update_user.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        username: form.value.username,
        email: form.value.email,
        phone: form.value.phone,
        last_name: form.value.last_name,
      }),
    });

    const data = await response.json();

    if (data.success) {
      await Swal.fire({
        icon: "success",
        title: "Success!",
        text: "User updated successfully!",
        timer: 2000,
        showConfirmButton: false,
      });
      user.value = { ...user.value, ...form.value };
      editing.value = false;
    } else {
      await Swal.fire({
        icon: "error",
        title: "Error",
        text: `Failed to update user: ${data.message}`,
      });
    }
  } catch (error) {
    console.error("Error updating user:", error);
    await Swal.fire({
      icon: "error",
      title: "Error",
      text: "An error occurred while updating the user.",
    });
  } finally {
    loading.value = false;
  }
}
</script>
