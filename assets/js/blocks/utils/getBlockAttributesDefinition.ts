import { BlockAttributesDefinition } from '../types';

export function getBlockAttributesDef(
	blockName: string
): BlockAttributesDefinition {
	return wp.blocks.getBlockType(blockName).attributes;
}
