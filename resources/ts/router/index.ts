import { setupLayouts } from 'virtual:generated-layouts';
import { createRouter, createWebHistory } from 'vue-router';
import routes from '~pages';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/user-profile/:tab(profile|connections)?',
      redirect: () => ({ name: 'user-user-profile-tab', params: { tab: 'profile' } }),
    },
    {
      path: '/post/:id',
      name: 'PostDetail',
      component: () => import('@/pages/user-profile/post/[id].vue'),
    },
    ...setupLayouts(routes),
  ],
})

router.beforeEach(async (to, from) => {
  let user  = localStorage.getItem('userData');
  const isAuthenticated = !!user // Check if the user is logged in

  // Allow access to sign up and sign in pages without authentication
  if (to.name === "login" || to.name === "register") {
    return true;
  }

  // Redirect to login if the user is not authenticated
  if (!isAuthenticated) {
    return { name: "login" };
  }

  return true; // Allow access to the requested route
});

export default router
