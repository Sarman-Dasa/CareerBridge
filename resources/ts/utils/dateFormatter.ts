import moment from 'moment';

export function formatDate(date: string | Date, dateFormat = 'DD-MM-YYYY'): string {
  return moment(date).format(dateFormat);
}
