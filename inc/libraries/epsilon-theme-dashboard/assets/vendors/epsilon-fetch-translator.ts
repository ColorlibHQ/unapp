export class EpsilonFetchTranslator {
  /**
   * Constructor
   * @param {Object} args
   */
  public constructor( args: { action: any, nonce: any, args: any } ) {
    return {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
      },
      body: 'action=' + args.action + '&_wpnonce=' + args.nonce + '&args=' + JSON.stringify( args.args ),
      credentials: 'same-origin'
    };
  }
}
