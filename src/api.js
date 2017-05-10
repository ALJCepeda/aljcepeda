import _ from '_';
import mock from './mock.js';

export default {
  test: mock['addresses'],
  name: 'Alfred Cepeda',
  image: require('./assets/images/me.jpeg'),
  keyInject: {
    'first': function(coll, offset) {
      let index = 0 + (offset || 0);
      return coll[index];
    },
    'last': function(coll, offset) {
      let index = (coll.length - 1) - (offset || 0);
      return coll[index];
    }
  },
  all: function(coll) {
    return new Promise((resolve, reject) => {
      resolve(mock[coll]);
    });
  },
  id: function(coll, id) {
    return mock[coll].find((entry) => {
      return entry.id === id;
    });
  },
  key: function(coll, key, offset) {
    var result = mock[coll][key];

    if (_.isUndefined(result)) {
      var inject = this.keyInject[key];

      if (_.isFunction(inject)) {
        result = inject(mock[coll], offset);
      }
    }

    return result;
  }
};