import React, { Component }  from 'react';

class Table extends Component {
    
    render() {

    const totalWeightRow = this.props.selectedEntitlement != -1 ? (<tr>
                                <td>
                                <h3>Total Weight of Household Goods {this.props.isDependencies ? `(with dependents)` : ""}</h3>

                                <ul>
                                    <li>Excludes Pro-Gear (items used professionally for work) and your <abbr title="Privately-Owned Vehicle">POV</abbr> vehicles (Primary Vehicles)</li>
                                    <li>Using a <a href="/resources/weight-estimator">weight estimation tool</a> can help you prepare for your move</li>
                                </ul>
                                </td>
                                <td>
                                <b>{this.props.isDependencies ? this.props.entitlements[this.props.selectedEntitlement].total_weight_self_plus_dependents :
                                    this.props.entitlements[this.props.selectedEntitlement].total_weight_self } lbs.</b>
                                </td>
                            </tr>): null;
    
    const proGearRow = (this.props.selectedEntitlement != -1 && 
                        this.props.entitlements[this.props.selectedEntitlement].pro_gear_weight > 0) ? 
                        (<tr>
                            <td>
                            <h3>Service Member Pro-Gear (Work-related equipment &amp; gear)</h3>

                            <ul>
                                <li>Professional equipment must be completely separated from the rest of your items so that they can be packed, marked, and weighed separately.</li>
                                <li><a href="#TODO">What is considered pro-gear?</a></li>
                            </ul>
                            </td>
                            <td>
                            <b>+ {this.props.entitlements[this.props.selectedEntitlement].pro_gear_weight} lbs.</b>
                            <i>(not included in total weight of household goods)</i>
                            </td>
                        </tr>) : null;

    const spouseProGearRow = (this.props.selectedEntitlement != -1 && 
                              this.props.isDependencies && 
                              this.props.entitlements[this.props.selectedEntitlement].pro_gear_weight_spouse > 0) ?
                             (<tr>
                                <td>
                                  <h3>Spouse Pro-Gear (Work-related equipment &amp; gear)</h3>

                                  <ul>
                                    <li>Professional equipment must be completely separated from your spouse’s pro-gear and from the rest of your family’s items. All of your pro-gear will be packed, marked, and weighed separately.</li>
                                    <li>Spouses have the same limitations as the service member regarding what can be considered “Pro-Gear.” See the service member pro-gear section above for more details.</li>
                                  </ul>
                                </td>
                                <td>
                                  <b>+ {this.props.entitlements[this.props.selectedEntitlement].pro_gear_weight_spouse} lbs.</b>
                                  <i>(not included in total weight of household goods)</i>
                                </td>
                              </tr>) : null;

    const oconusAlertMsg = !this.props.isConus ? 
                            (<div class="usa-alert usa-alert-warning">
                                <div class="usa-alert-body">
                                     <p class="usa-alert-text"><strong>Important:</strong> Certain overseas (<abbr title="Outside the Continental United States">OCONUS</abbr>) locations have weight restrictions due to limited housing and storage availability. If you are moving to an overseas (<abbr title="Outside the Continental United States">OCONUS</abbr>) location with restrictions any excess weight above your restricted weight up to your full weight entitlement can be placed in stateside longterm "non-temporary" storage (NTS).</p>
                                </div>
                            </div>) : null ;

    return this.props.selectedEntitlement != -1 ? (<div className ="table">
                        <ul className="usa-unstyled-list">
                          <li> Military pay grade: <b>{this.props.entitlements[this.props.selectedEntitlement].rank}</b> </li>
                          <li> Dependency Status: 
                                <b> 
                                    {this.props.isDependencies ? ` Yes, I have dependents (spouse/children) that are authorized to move`:
                                                                 ` No, I do not have dependents`} 
                                </b> 
                         </li>
                          <li> Move type: <b> {this.props.isConus ? `CONUS` : `OCONUS`} </b> </li>
                        </ul>

                        <table class="entitlements-table">
                          <tbody>
                              {totalWeightRow}
                              {proGearRow}
                              {spouseProGearRow}
                          </tbody>
                        </table>
                        {oconusAlertMsg}
                      </div>
      ) : null;
    }
}

export default Table;
