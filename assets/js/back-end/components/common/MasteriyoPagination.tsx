import {
	Pagination,
	PaginationContainer,
	PaginationNext,
	PaginationPage,
	PaginationPageGroup,
	PaginationPrevious,
	PaginationSeparator,
	usePagination,
} from '@ajna/pagination';
import { HStack, Select, Stack, Text } from '@chakra-ui/react';
import { sprintf, __ } from '@wordpress/i18n';
import React from 'react';
import { FaChevronLeft, FaChevronRight } from 'react-icons/fa';
import { isRightDir } from '../../utils/utils';

interface MetaData {
	total: number;
	pages: number;
	current_page: number;
	per_page: number;
}

interface Props {
	metaData: MetaData;
	setFilterParams: any;
	perPageText: string;
	outerLimits?: number;
	innerLimits?: number;
	extraFilterParams?: any;
	showPerPage?: boolean;
}

const MasteriyoPagination: React.FC<Props> = (props) => {
	const {
		metaData,
		setFilterParams,
		perPageText,
		outerLimits = 2,
		innerLimits = 2,
		extraFilterParams,
		showPerPage = true,
	} = props;

	const {
		pages,
		pagesCount,
		currentPage,
		setCurrentPage,
		pageSize,
		setPageSize,
	} = usePagination({
		total: metaData?.total,
		limits: {
			outer: outerLimits,
			inner: innerLimits,
		},
		initialState: {
			pageSize: metaData?.per_page,
			isDisabled: false,
			currentPage: metaData?.current_page,
		},
	});

	const handlePageChange = (nextPage: number): void => {
		setFilterParams({
			page: nextPage,
			per_page: pageSize,
			...extraFilterParams,
		});
		setCurrentPage(nextPage);
	};

	const handlePageSizeChange = (event: any): void => {
		const pageSize = Number(event.target.value);
		setFilterParams({
			per_page: pageSize,
			...extraFilterParams,
		});
		setPageSize(pageSize);
	};

	// Current page highest value. For e.g if 1 - 10, 10 is highest.
	const currentPageHighest = metaData?.per_page * metaData?.current_page;

	// Current page lowest value. For e.g if 1 - 10, 1 is lowest.
	const displayCurrentPageLowest = currentPageHighest - metaData?.per_page + 1;

	// Setting highest value depending on current page is last page or not.
	const displayCurrentPageHighest =
		metaData?.current_page === metaData?.pages
			? metaData?.total
			: currentPageHighest;

	return (
		<Stack
			mt="8"
			w="full"
			direction={['column', null, 'row']}
			justifyContent="space-between"
			pb="4"
			fontSize={['xs', null, 'sm']}>
			<Text color="gray.500">
				{sprintf(
					/* translators: %1$d: shown results from, %2$d shown results to, %3$d total count */
					__('Showing %d - %d out of %d', 'masteriyo'),
					displayCurrentPageLowest,
					displayCurrentPageHighest,
					metaData?.total
				)}
			</Text>
			<HStack>
				{showPerPage ? (
					<>
						<Text color="gray.500">{perPageText}</Text>
						<Select
							size="sm"
							defaultValue={metaData?.per_page}
							ml={3}
							onChange={handlePageSizeChange}
							w={20}>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
							<option value="50">50</option>
						</Select>
					</>
				) : null}
				<Pagination
					pagesCount={pagesCount}
					currentPage={currentPage}
					onPageChange={handlePageChange}>
					<PaginationContainer sx={{ li: { m: '0' } }}>
						<Stack direction="row" spacing="1">
							<PaginationPrevious size="sm" shadow="none">
								{isRightDir ? <FaChevronRight /> : <FaChevronLeft />}
							</PaginationPrevious>
							<PaginationPageGroup
								isInline
								align="center"
								separator={
									<PaginationSeparator fontSize="sm" w={7} jumpSize={3} />
								}>
								{pages.map((page: number) => (
									<PaginationPage
										shadow="none"
										h="8"
										w="8"
										key={`pagination_page_${page}`}
										page={page}
										_hover={{
											bg: 'primary.400',
										}}
										_current={{
											bg: 'primary.400',
											fontSize: 'sm',
											color: 'white',
										}}
									/>
								))}
							</PaginationPageGroup>
							<PaginationNext size="sm" shadow="none">
								{isRightDir ? <FaChevronLeft /> : <FaChevronRight />}
							</PaginationNext>
						</Stack>
					</PaginationContainer>
				</Pagination>
			</HStack>
		</Stack>
	);
};

export default MasteriyoPagination;
