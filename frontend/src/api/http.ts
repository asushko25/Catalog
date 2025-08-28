import axios from 'axios';

export const http = axios.create({
  baseURL: '/api',
  timeout: 10000,
});

// Единая обработка ошибок
http.interceptors.response.use(
  (r) => r,
  (err) => {
    const data = err?.response?.data;

    // Поддержка двух форматов:
    // { errors: [{field, message}] }  — наш контроллер
    // { violations: [{ propertyPath, title }] } — стандартный symfony
    const apiErrors =
      data?.errors ??
      (Array.isArray(data?.violations)
        ? data.violations.map((v: any) => ({
            field: v.propertyPath,
            message: v.title,
          }))
        : data?.message
        ? [{ message: data.message }]
        : [{ message: 'Unknown error' }]);

    // ВАЖНО: именно возвращаем Promise.reject, а не throw
    return Promise.reject({ ...err, apiErrors });
  }
);
