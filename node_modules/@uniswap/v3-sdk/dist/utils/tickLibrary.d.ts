import JSBI from 'jsbi';
interface FeeGrowthOutside {
    feeGrowthOutside0X128: JSBI;
    feeGrowthOutside1X128: JSBI;
}
export declare function subIn256(x: JSBI, y: JSBI): JSBI;
export declare abstract class TickLibrary {
    /**
     * Cannot be constructed.
     */
    private constructor();
    static getFeeGrowthInside(feeGrowthOutsideLower: FeeGrowthOutside, feeGrowthOutsideUpper: FeeGrowthOutside, tickLower: number, tickUpper: number, tickCurrent: number, feeGrowthGlobal0X128: JSBI, feeGrowthGlobal1X128: JSBI): JSBI[];
}
export {};
